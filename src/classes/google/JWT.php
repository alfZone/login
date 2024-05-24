<?php

namespace classes\google;

use ArrayAccess;
use DateTime;
use DomainException;
use Exception;
use InvalidArgumentException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use stdClass;
use UnexpectedValueException;
use classes\google\SignatureInvalidException;
/**
 * JSON Web Token implementation, based on this spec:
 * https://tools.ietf.org/html/rfc7519
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */
class JWT{
    private const ASN1_INTEGER = 0x02;
    private const ASN1_SEQUENCE = 0x10;
    private const ASN1_BIT_STRING = 0x03;

    /**
     * When checking nbf, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
     *
     * @var int
     */
    public static $leeway = 0;

    /**
     * Allow the current timestamp to be specified.
     * Useful for fixing a value within unit testing.
     * Will default to PHP time() value if null.
     *
     * @var ?int
     */
    public static $timestamp = null;

    /**
     * @var array<string, string[]>
     */
    public static $supported_algs = [
        'ES384' => ['openssl', 'SHA384'],
        'ES256' => ['openssl', 'SHA256'],
        'ES256K' => ['openssl', 'SHA256'],
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
        'EdDSA' => ['sodium_crypto', 'EdDSA'],
    ];

    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string                 $jwt            The JWT
     * @param Key|ArrayAccess<string,Key>|array<string,Key> $keyOrKeyArray  The Key or associative array of key IDs
     *                                                                      (kid) to Key objects.
     *                                                                      If the algorithm used is asymmetric, this is
     *                                                                      the public key.
     *                                                                      Each Key object contains an algorithm and
     *                                                                      matching key.
     *                                                                      Supported algorithms are 'ES384','ES256',
     *                                                                      'HS256', 'HS384', 'HS512', 'RS256', 'RS384'
     *                                                                      and 'RS512'.
     * @param stdClass               $headers                               Optional. Populates stdClass with headers.
     *
     * @return stdClass The JWT's payload as a PHP object
     *
     * @throws InvalidArgumentException     Provided key/key-array was empty or malformed
     * @throws DomainException              Provided JWT is malformed
     * @throws UnexpectedValueException     Provided JWT was invalid
     * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim
     *
     * @uses jsonDecode
     * @uses urlsafeB64Decode
     */
    public static function decode(
        string $jwt,
        $keyOrKeyArray,
        stdClass &$headers = null
    ): stdClass {
        // Validate JWT
        $timestamp = \is_null(static::$timestamp) ? \time() : static::$timestamp;

        if (empty($keyOrKeyArray)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        $tks = \explode('.', $jwt);
        if (\count($tks) !== 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $headerRaw = static::urlsafeB64Decode($headb64);
        if (null === ($header = static::jsonDecode($headerRaw))) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        if ($headers !== null) {
            $headers = $header;
        }
        $payloadRaw = static::urlsafeB64Decode($bodyb64);
        if (null === ($payload = static::jsonDecode($payloadRaw))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (\is_array($payload)) {
            // prevent PHP Fatal Error in edge-cases when payload is empty array
            $payload = (object) $payload;
        }
        if (!$payload instanceof stdClass) {
            throw new UnexpectedValueException('Payload must be a JSON object');
        }
        $sig = static::urlsafeB64Decode($cryptob64);
        if (empty($header->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (empty(static::$supported_algs[$header->alg])) {
            throw new UnexpectedValueException('Algorithm not supported');
        }

        $key = self::getKey($keyOrKeyArray, property_exists($header, 'kid') ? $header->kid : null);

        // Check the algorithm
        if (!self::constantTimeEquals($key->getAlgorithm(), $header->alg)) {
            // See issue #351
            throw new UnexpectedValueException('Incorrect key for this algorithm');
        }
        if (\in_array($header->alg, ['ES256', 'ES256K', 'ES384'], true)) {
            // OpenSSL expects an ASN.1 DER sequence for ES256/ES256K/ES384 signatures
            $sig = self::signatureToDER($sig);
        }
        if (!self::verify("{$headb64}.{$bodyb64}", $sig, $key->getKeyMaterial(), $header->alg)) {
            throw new SignatureInvalidException('Signature verification failed');
        }

        // Check the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
        if (isset($payload->nbf) && floor($payload->nbf) > ($timestamp + static::$leeway)) {
            $ex = new BeforeValidException(
                'Cannot handle token with nbf prior to ' . \date(DateTime::ISO8601, (int) $payload->nbf)
            );
            $ex->setPayload($payload);
            throw $ex;
        }

        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (!isset($payload->nbf) && isset($payload->iat) && floor($payload->iat) > ($timestamp + static::$leeway)) {
            $ex = new BeforeValidException(
                'Cannot handle token with iat prior to ' . \date(DateTime::ISO8601, (int) $payload->iat)
            );
            $ex->setPayload($payload);
            throw $ex;
        }

        // Check if this token has expired.
        if (isset($payload->exp) && ($timestamp - static::$leeway) >= $payload->exp) {
            $ex = new ExpiredException('Expired token');
            $ex->setPayload($payload);
            throw $ex;
        }

        return $payload;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     *
     * @throws InvalidArgumentException invalid base64 characters
     */
    public static function urlsafeB64Decode(string $input): string{
        return \base64_decode(self::convertBase64UrlToBase64($input));
    }
  
  
      /**
     * Convert a string in the base64url (URL-safe Base64) encoding to standard base64.
     *
     * @param string $input A Base64 encoded string with URL-safe characters (-_ and no padding)
     *
     * @return string A Base64 encoded string with standard characters (+/) and padding (=), when
     * needed.
     *
     * @see https://www.rfc-editor.org/rfc/rfc4648
     */
    public static function convertBase64UrlToBase64(string $input): string
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \strtr($input, '-_', '+/');
    }
  
      /**
     * Decode a JSON string into a PHP object.
     *
     * @param string $input JSON string
     *
     * @return mixed The decoded JSON string
     *
     * @throws DomainException Provided string was invalid JSON
     */
    public static function jsonDecode(string $input)
    {
        $obj = \json_decode($input, false, 512, JSON_BIGINT_AS_STRING);

        if ($errno = \json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($obj === null && $input !== 'null') {
            throw new DomainException('Null result with non-null input');
        }
        return $obj;
    }
  
      /**
     * Determine if an algorithm has been provided for each Key
     *
     * @param Key|ArrayAccess<string,Key>|array<string,Key> $keyOrKeyArray
     * @param string|null            $kid
     *
     * @throws UnexpectedValueException
     *
     * @return Key
     */
    private static function getKey(
        $keyOrKeyArray,
        ?string $kid
    ): Key {
        if ($keyOrKeyArray instanceof Key) {
            return $keyOrKeyArray;
        }

        if (empty($kid) && $kid !== '0') {
            throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
        }

        if ($keyOrKeyArray instanceof CachedKeySet) {
            // Skip "isset" check, as this will automatically refresh if not set
            return $keyOrKeyArray[$kid];
        }

        if (!isset($keyOrKeyArray[$kid])) {
            throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
        }

        return $keyOrKeyArray[$kid];
    }
  
      /**
     * @param string $left  The string of known length to compare against
     * @param string $right The user-supplied string
     * @return bool
     */
    public static function constantTimeEquals(string $left, string $right): bool
    {
        if (\function_exists('hash_equals')) {
            return \hash_equals($left, $right);
        }
        $len = \min(self::safeStrlen($left), self::safeStrlen($right));

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (\ord($left[$i]) ^ \ord($right[$i]));
        }
        $status |= (self::safeStrlen($left) ^ self::safeStrlen($right));

        return ($status === 0);
    }
  
      /**
     * Verify a signature with the message, key and method. Not all methods
     * are symmetric, so we must have a separate verify and sign method.
     *
     * @param string $msg         The original message (header and body)
     * @param string $signature   The original signature
     * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $keyMaterial For Ed*, ES*, HS*, a string key works. for RS*, must be an instance of OpenSSLAsymmetricKey
     * @param string $alg         The algorithm
     *
     * @return bool
     *
     * @throws DomainException Invalid Algorithm, bad key, or OpenSSL failure
     */
    private static function verify(
        string $msg,
        string $signature,
        $keyMaterial,
        string $alg
    ): bool {
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }

        list($function, $algorithm) = static::$supported_algs[$alg];
        switch ($function) {
            case 'openssl':
                $success = \openssl_verify($msg, $signature, $keyMaterial, $algorithm); // @phpstan-ignore-line
                if ($success === 1) {
                    return true;
                }
                if ($success === 0) {
                    return false;
                }
                // returns 1 on success, 0 on failure, -1 on error.
                throw new DomainException(
                    'OpenSSL error: ' . \openssl_error_string()
                );
            case 'sodium_crypto':
                if (!\function_exists('sodium_crypto_sign_verify_detached')) {
                    throw new DomainException('libsodium is not available');
                }
                if (!\is_string($keyMaterial)) {
                    throw new InvalidArgumentException('key must be a string when using EdDSA');
                }
                try {
                    // The last non-empty line is used as the key.
                    $lines = array_filter(explode("\n", $keyMaterial));
                    $key = base64_decode((string) end($lines));
                    if (\strlen($key) === 0) {
                        throw new DomainException('Key cannot be empty string');
                    }
                    if (\strlen($signature) === 0) {
                        throw new DomainException('Signature cannot be empty string');
                    }
                    return sodium_crypto_sign_verify_detached($signature, $msg, $key);
                } catch (Exception $e) {
                    throw new DomainException($e->getMessage(), 0, $e);
                }
            case 'hash_hmac':
            default:
                if (!\is_string($keyMaterial)) {
                    throw new InvalidArgumentException('key must be a string when using hmac');
                }
                $hash = \hash_hmac($algorithm, $msg, $keyMaterial, true);
                return self::constantTimeEquals($hash, $signature);
        }
    }

  

}
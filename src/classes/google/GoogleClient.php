<?php
namespace classes\google;
/*
 * Copyright 2010 Google Inc.
 *
 * A light class than the googleClient
 */
use DateTime;
//use classes\firebaseJWT\JWT;
use classes\google\JWT;
use classes\google\Key;
//use classes\firebaseJWT\Key;
//use classes\firebaseJWT\SignatureInvalidException;
use classes\google\SignatureInvalidException;
use BadMethodCallException;
use DomainException;
use UnexpectedValueException;

use phpseclib3\Crypt\PublicKeyLoader; //tratar disto
use phpseclib3\Crypt\RSA\PublicKey; // Firebase v2

class GoogleClient{
    const LIBVER = "2.12.6";
    const API_BASE_PATH = 'https://www.googleapis.com';
    const FEDERATED_SIGNON_CERT_URL = 'https://www.googleapis.com/oauth2/v3/certs';

    /**
     * @var \Firebase\JWT\JWT
    */
    public $jwt;

    /**
     * @var array access token
     */
    private $token;

    /**
     * @var array $config
     */
    private $config;

    /**
     * Construct the Google Client.
     *
     * @param array $config
     */
    public function __construct(array $config = []){
        $this->config = array_merge([
            'application_name' => '',
            // Don't change these unless you're working against a special development
            // or testing environment.
            'base_path' => self::API_BASE_PATH,
            // https://developers.google.com/console
            'client_id' => '',
            // Service class used in Google\Client::verifyIdToken.
            // Explicitly pass this in to avoid setting JWT::$leeway
            'jwt' => null,
        ], $config);
 
   }

      /**
     * Verify an id_token. This method will verify the current id_token, if one
     * isn't provided.
     *
     * @throws LogicException If no token was provided and no token was set using `setAccessToken`.
     * @throws UnexpectedValueException If the token is not a valid JWT.
     * @param string|null $idToken The token (id_token) that should be verified.
     * @return array|false Returns the token payload as an array if the verification was
     * successful, false otherwise.
     */
    public function verifyIdToken($idToken = null){      
        $this->jwt = new JWT();
        $this->jwt::$leeway = 1;

        //o token não foi passado no método
        if (null === $idToken) {
            $token = $this->getAccessToken();
            if (!isset($token['id_token'])) {
                throw new LogicException(
                    'id_token must be passed in or set as part of setAccessToken'
                );
            }
            $idToken = $token['id_token'];
        }
        // set phpseclib constants if applicable
        $this->setPhpsecConstants();
        // Check signature
        $certs = $this->fetchGooglePublicKeys();
        foreach ($certs as $cert) {
           try {
                $args = [$idToken];
                $publicKey = $this->getPublicKey($cert);
                $args[] = new Key($publicKey, 'RS256');
                $payload=$this->jwt->decode($args[0],$args[1]);
                return (array) $payload;
            } catch (ExpiredException $e) { // @phpstan-ignore-line
                return false;
            } catch (ExpiredExceptionV3 $e) {
                return false;
            } catch (SignatureInvalidException $e) {
                // continue
            } catch (DomainException $e) {
                // continue
            } 
        }
        return false;
    }
  
  
  /**
     * Busca as chaves públicas do Google.
     *
     * @return array
     */
    private function fetchGooglePublicKeys(): array{
        $json = file_get_contents( self::FEDERATED_SIGNON_CERT_URL);
        $keys = json_decode($json, true);

        $parsedKeys = [];
        $i=0;
        foreach ($keys['keys'] as $key) {
            $parsedKeys[$i] = $key;
            $i++;
        }
        return $parsedKeys;
    }
    
  /**
     * Set the OAuth 2.0 Client ID.
     * @param string $clientId
     */
    public function setClientId($clientId){
        $this->config['client_id'] = $clientId;
    }

    public function getClientId(){
        return $this->config['client_id'];
    }
      
    private function getBigIntClass()    {
        if (class_exists('phpseclib3\Math\BigInteger')) {
            return 'phpseclib3\Math\BigInteger';
        }

        if (class_exists('phpseclib\Math\BigInteger')) {
            return 'phpseclib\Math\BigInteger';
        }

        return 'Math_BigInteger';
    }
  
  private function getPublicKey($cert){
        $bigIntClass = $this->getBigIntClass();
        $modulus = new $bigIntClass($this->jwt->urlsafeB64Decode($cert['n']), 256);
        $exponent = new $bigIntClass($this->jwt->urlsafeB64Decode($cert['e']), 256);
        $component = ['n' => $modulus, 'e' => $exponent];

        if (class_exists('phpseclib3\Crypt\RSA\PublicKey')) {
            /** @var PublicKey $loader */
            $loader = PublicKeyLoader::load($component);

            return $loader->toString('PKCS8');
        }

        $rsaClass = $this->getRsaClass();
        $rsa = new $rsaClass();
        $rsa->loadKey($component);

        return $rsa->getPublicKey();
    }

   private function setPhpsecConstants(){
        if (filter_var(getenv('GAE_VM'), FILTER_VALIDATE_BOOLEAN)) {
            if (!defined('MATH_BIGINTEGER_OPENSSL_ENABLED')) {
                define('MATH_BIGINTEGER_OPENSSL_ENABLED', true);
            }
            if (!defined('CRYPT_RSA_MODE')) {
                define('CRYPT_RSA_MODE', constant($this->getOpenSslConstant()));
            }
        }
    }
  
    private function getOpenSslConstant(){
        if (class_exists('phpseclib3\Crypt\AES')) {
            return 'phpseclib3\Crypt\AES::ENGINE_OPENSSL';
        }

        if (class_exists('phpseclib\Crypt\RSA')) {
            return 'phpseclib\Crypt\RSA::MODE_OPENSSL';
        }

        if (class_exists('Crypt_RSA')) {
            return 'CRYPT_RSA_MODE_OPENSSL';
        }

        throw new Exception('Cannot find RSA class');
    }
  
  
}

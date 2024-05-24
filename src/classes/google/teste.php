<?php
if (extension_loaded('openssl')) {
    echo 'OpenSSL está habilitado.';
    if (function_exists('openssl_pkey_get_public')) {
        echo 'A função openssl_pkey_get_public() está disponível.';
    } else {
        echo 'A função openssl_pkey_get_public() NÃO está disponível.';
    }
} else {
    echo 'OpenSSL NÃO está habilitado.';
}
?>
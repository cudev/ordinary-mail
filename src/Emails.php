<?php

namespace Cudev\OrdinaryMail;

use RuntimeException;

class Emails
{
    const DEFAULT_CIPHER_METHOD = 'AES-256-CBC';
    const CIPHER_HASH = 'ThereWillComeSoftRains';

    private static $cipherHash;

    public static function encrypt(string $email, string $cipherMethod = self::DEFAULT_CIPHER_METHOD): string
    {
        self::checkCipherHash();
        $vector = self::createVector(self::getVectorLength($cipherMethod));
        return bin2hex($vector) . openssl_encrypt(strtolower($email), $cipherMethod, self::$cipherHash, false, $vector);
    }

    public static function decrypt(string $encrypted, string $cipherMethod = self::DEFAULT_CIPHER_METHOD): string
    {
        self::checkCipherHash();
        // Multiply the vector length twice because we have a UTF-8 string that uses 2 bytes for a char
        $vectorLength = self::getVectorLength($cipherMethod) * 2;
        $vector = substr($encrypted, 0, $vectorLength);
        $decryptedEmail = substr($encrypted, $vectorLength);
        return openssl_decrypt($decryptedEmail, $cipherMethod, self::$cipherHash, false, hex2bin($vector));
    }

    private static function createVector(int $length)
    {
        return random_bytes($length);
    }

    private static function getVectorLength(string $cipherMethod = self::DEFAULT_CIPHER_METHOD)
    {
        return openssl_cipher_iv_length($cipherMethod);
    }

    public static function setCipherHash(string $cipherHash)
    {
        self::$cipherHash = $cipherHash;
    }

    private static function checkCipherHash()
    {
        if (self::$cipherHash === null) {
            throw new RuntimeException(sprintf('Cipher hash has not been set'));
        }
    }
}

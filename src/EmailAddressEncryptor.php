<?php

/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Cudev Ltd.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Cudev\OrdinaryMail;

use InvalidArgumentException;

class EmailAddressEncryptor
{
    const DEFAULT_CIPHER_METHOD = 'AES-256-CBC';

    private $key;
    private $cipher;

    /**
     * EmailAddressEncryptor constructor.
     * @param string $key
     * @param string $cipher
     * @throws InvalidArgumentException
     */
    public function __construct(string $key, string $cipher = self::DEFAULT_CIPHER_METHOD)
    {
        if (!$this->isCipherAvailable($cipher)) {
            throw new InvalidArgumentException(sprintf('Cipher method %s is not available on this system', $cipher));
        }
        $this->cipher = $cipher;
        $this->key = $key;
    }

    /**
     * @param string $email
     * @return string
     */
    public function encrypt(string $email): string
    {
        $vector = self::createVector(self::getVectorLength($this->cipher));
        return bin2hex($vector) . openssl_encrypt(strtolower($email), $this->cipher, $this->key, false, $vector);
    }

    /**
     * @param string $encrypted
     * @return string
     */
    public function decrypt(string $encrypted): string
    {
        // Multiply the vector length twice because we have a UTF-8 string that uses 2 bytes for a char
        $vectorLength = self::getVectorLength($this->cipher) * 2;
        $vector = substr($encrypted, 0, $vectorLength);
        $decryptedEmail = substr($encrypted, $vectorLength);
        return openssl_decrypt($decryptedEmail, $this->cipher, $this->key, false, hex2bin($vector));
    }

    /**
     * @param string $cipher
     * @return bool
     */
    private function isCipherAvailable(string $cipher): bool
    {
        return in_array($cipher, openssl_get_cipher_methods(), true);
    }

    /**
     * @param int $length
     * @return string
     */
    private static function createVector(int $length): string
    {
        return random_bytes($length);
    }

    /**
     * @param string $cipherMethod
     * @return int
     */
    private static function getVectorLength(string $cipherMethod = self::DEFAULT_CIPHER_METHOD): int
    {
        return openssl_cipher_iv_length($cipherMethod);
    }
}

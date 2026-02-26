<?php

namespace App\Security;

class CryptoService {
    private const CIPHER = 'aes-256-gcm';

    public static function encrypt(string $data, string $userKey): array {
        $iv = random_bytes(openssl_cipher_iv_length(self::CIPHER));
        $tag = '';
        $encrypted = openssl_encrypt($data, self::CIPHER, $userKey, OPENSSL_RAW_DATA, $iv, $tag);
        return [
            'data' => base64_encode($encrypted),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag)
        ];
    }

    public static function decrypt(string $encryptedData, string $userKey, string $iv, string $tag): ?string {
        $decrypted = openssl_decrypt(
            base64_decode($encryptedData),
            self::CIPHER,
            $userKey,
            OPENSSL_RAW_DATA,
            base64_decode($iv),
            base64_decode($tag)
        );
        return $decrypted !== false ? $decrypted : null;
    }

    public static function generateUserKey(): string {
        return random_bytes(32); // 256 bits
    }
}

<?php

namespace App\Security;

class JwtService
{
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generateTokens(array $payload): array
    {
        $accessTokenPayload = array_merge($payload, ['exp' => time() + (15 * 60)]);
        $refreshTokenPayload = array_merge($payload, ['exp' => time() + (7 * 24 * 60 * 60), 'type' => 'refresh']);

        return [
            'access_token' => $this->encode($accessTokenPayload),
            'refresh_token' => $this->encode($refreshTokenPayload)
        ];
    }

    private function encode(array $payload): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
}

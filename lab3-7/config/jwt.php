<?php

const JWT_SECRET = 'super_secret_key_123';

function base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode(string $data): string {
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_create(array $payload): string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload['exp'] = time() + 3600 * 24 * 365;

    $h = base64url_encode(json_encode($header));
    $p = base64url_encode(json_encode($payload));
    $s = base64url_encode(
        hash_hmac('sha256', "$h.$p", JWT_SECRET, true)
    );

    return "$h.$p.$s";
}

function jwt_verify(string $jwt): ?array {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return null;

    [$h, $p, $s] = $parts;

    $valid = base64url_encode(
        hash_hmac('sha256', "$h.$p", JWT_SECRET, true)
    );

    if (!hash_equals($valid, $s)) return null;

    $payload = json_decode(base64url_decode($p), true);

    if (($payload['exp'] ?? 0) < time()) return null;

    return $payload;
}

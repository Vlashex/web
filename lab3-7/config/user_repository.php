<?php

function email_exists(PDO $pdo, string $email, ?int $excludeUserId = null): bool
{
    if ($excludeUserId) {
        $stmt = $pdo->prepare(
            "SELECT 1 FROM users WHERE email = ? AND id != ? LIMIT 1"
        );
        $stmt->execute([$email, $excludeUserId]);
    } else {
        $stmt = $pdo->prepare(
            "SELECT 1 FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
    }

    return (bool) $stmt->fetchColumn();
}
function phone_exists(PDO $pdo, string $phone, ?int $excludeUserId = null): bool
{
    if ($excludeUserId !== null) {
        $stmt = $pdo->prepare(
            "SELECT 1 FROM users WHERE phone = ? AND id != ? LIMIT 1"
        );
        $stmt->execute([$phone, $excludeUserId]);
    } else {
        $stmt = $pdo->prepare(
            "SELECT 1 FROM users WHERE phone = ? LIMIT 1"
        );
        $stmt->execute([$phone]);
    }

    return (bool) $stmt->fetchColumn();
}
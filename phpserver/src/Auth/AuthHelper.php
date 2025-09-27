<?php
// src/Auth/AuthHelper.php

class AuthHelper {
    public static function hashPassword($password) {
        // Use Argon2i for strong password hashing
        return password_hash($password, PASSWORD_ARGON2I);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function setSecureSession($userId, $role) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict',
            ]);
            session_start();
        }
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
    }

    public static function setRememberMeCookie($userId) {
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        // Store $hashedToken in DB with $userId and expiry (not implemented here)
        setcookie('remember_me', $token, [
            'expires' => time() + 60*60*24*30, // 30 days
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Strict',
            'path' => '/',
        ]);
    }
}

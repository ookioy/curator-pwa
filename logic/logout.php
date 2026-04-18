<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';
require 'helpers.php';

if (!empty($_COOKIE['auth_token'])) {
    try {
        $stmt = $pdo->query('SELECT id, token_hash FROM auth_tokens');
        foreach ($stmt->fetchAll() as $row) {
            if (password_verify($_COOKIE['auth_token'], $row['token_hash'])) {
                $pdo->prepare('DELETE FROM auth_tokens WHERE id = ?')->execute([$row['id']]);
                break;
            }
        }
    } catch (Exception $e) {}

    setcookie('auth_token', '', time() - 3600, '/');
}

$_SESSION = [];
session_destroy();
redirect('../login.php');

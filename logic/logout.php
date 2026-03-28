<?php
session_start();
require 'db.php';

if (isset($_COOKIE['auth_token'])) {
    try {
        $token = $_COOKIE['auth_token'];
        
        $stmt = $pdo->prepare("SELECT id, token_hash FROM auth_tokens");
        $stmt->execute();
        $tokens = $stmt->fetchAll();
        
        foreach ($tokens as $row) {
            if (password_verify($token, $row['token_hash'])) {
                $delete = $pdo->prepare("DELETE FROM auth_tokens WHERE id = ?");
                $delete->execute([$row['id']]);
                break;
            }
        }
    } catch (Exception $e) {}
    
    setcookie('auth_token', '', time() - 3600, "/");
}

session_destroy();

header('Location: ../login.php');
exit;
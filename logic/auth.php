<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth(PDO $pdo): bool
{
    if (!empty($_SESSION['logged_in'])) {
        return true;
    }

    if (empty($_COOKIE['auth_token'])) {
        return false;
    }

    try {
        $stmt = $pdo->prepare(
            'SELECT token_hash FROM auth_tokens WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)'
        );
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            if (password_verify($_COOKIE['auth_token'], $row['token_hash'])) {
                $_SESSION['logged_in'] = true;
                return true;
            }
        }
    } catch (Exception $e) {}

    setcookie('auth_token', '', time() - 3600, '/');
    return false;
}

function protectPage(PDO $pdo): void
{
    if (checkAuth($pdo)) {
        return;
    }

    $inLogic = str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/logic/');
    header('Location: ' . ($inLogic ? '../login.php' : 'login.php'));
    exit;
}

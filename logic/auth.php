<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth($pdo) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }

    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        
        try {
            $stmt = $pdo->prepare("SELECT token_hash FROM auth_tokens WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stmt->execute();
            $tokens = $stmt->fetchAll();
            
            foreach ($tokens as $row) {
                if (password_verify($token, $row['token_hash'])) {
                    $_SESSION['logged_in'] = true;
                    return true;
                }
            }
            
            setcookie('auth_token', '', time() - 3600, "/");
            
        } catch (Exception $e) {
            setcookie('auth_token', '', time() - 3600, "/");
        }
    }
    
    return false;
}

function protectPage($pdo) {
    if (!checkAuth($pdo)) {
        // Визначаємо правильний шлях до login.php відносно кореня
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $inLogic = str_contains($script, '/logic/');
        $redirect = $inLogic ? '../login.php' : 'login.php';
        header('Location: ' . $redirect);
        exit;
    }
}
<?php
session_start();
require 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, is_admin FROM users WHERE username = :username');
        $stmt->bindParam(':username', $inputUsername, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($inputPassword, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];

            if ($user['is_admin']) {
                header("Location: ../../admin_panel.php");
            } else {
                header("Location: ../../profile.php");
            }
            exit();
        } else {
            $_SESSION['error'] = 'Nieprawidłowy login lub hasło.';
            header('Location: ../../index.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Wystąpił błąd podczas logowania.';
        header('Location: ../../index.php');
        exit();
    }
}

if (isset($_GET['logout'])) {

    $_SESSION = array();

    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    header('Location: ../../index.php');
    exit();
}
?>
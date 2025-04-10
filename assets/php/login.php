<?php
session_start();
require 'config.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :username');
        $stmt->bindParam(':username', $inputUsername, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($user && password_verify($inputPassword, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];

            
            header("Location: ../../profile.php");
            exit();
        } else {
            $_SESSION['error'] = 'Nieprawidłowy login lub hasło.';
            header('Location: ../../index.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Błąd podczas wykonywania zapytania: " . $e->getMessage());
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../index.php');
    exit();
}
?>

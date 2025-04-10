<?php
session_start();
require 'config.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    
    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Hasła się nie zgadzają!";
        header('Location: ../../register.php');
        exit();
    }

    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Użytkownik o tej nazwie już istnieje!";
        header('Location: ../../register.php');
        exit();
    }

    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
    $stmt->execute();

    
    $_SESSION['success'] = "Rejestracja przebiegła pomyślnie! Możesz się teraz zalogować.";
    header('Location: ../../register.php');
    exit();
}
?>

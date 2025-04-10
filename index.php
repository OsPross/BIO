<?php
session_start();


$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Logowanie</title>
</head>
<body>
  <div id="background"></div>
  <div id="datetime"></div>
  
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    
    <form action="assets/php/login.php" method="POST">
        <label id="log">Login:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label id="log">Hasło:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button class="log" type="submit">Zaloguj się</button>
        <button class="log"><a href="register.php">Załuż konto</a></button>
    </form>

    
      
    <script src="assets/js/stars.js"></script>
    <script src="assets/js/datetime.js"></script>

    <div id="watermark">coded by OsPros | 2025</div>
</body>
</html>

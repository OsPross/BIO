<?php
session_start();


if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Rejestracja</title>
</head>
<body>
<div id="background"></div>
<div id="datetime"></div>
    <form method="POST" action="assets/php/register_process.php">
        <h1>Rejestracja</h1>

        <label id="log" for="username">Login:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label id="log" for="password">Hasło:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label id="log" for="password_confirm">Potwierdź hasło:</label><br>
        <input type="password" id="password_confirm" name="password_confirm" required><br><br>

        <button class="log" type="submit">Zarejestruj się</button>
        <button class="log"><a href="index.php">Masz już konto? Zaloguj się tutaj</a></button>
    </form>

    <script src="assets/js/stars.js"></script>
    <script src="assets/js/datetime.js"></script>

    <div id="watermark">coded by OsPros | 2025</div>
</body>
</html>

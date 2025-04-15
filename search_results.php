<?php 
session_start();
require 'assets/php/config.php'; 
require 'assets/php/get_user_data.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

$user_data = getUserData($_SESSION['user_id']); 
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Wyniki wyszukiwania</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div id="background"></div>
<div id="datetime"></div>


<form method="GET" action="search_results.php" style="text-align: center; margin-top: 20px;">
  <input type="text" name="query" placeholder="Szukaj ponownie..." required>
  <button type="submit">Szukaj</button>
</form>

<div id="content-wrapper">
  <div id="main-box">
    <h2>Wyniki wyszukiwania:</h2>
    <?php
    if (isset($_GET['query'])) {
        $query = trim($_GET['query']);
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username LIKE :query");
        $stmt->execute(['query' => '%' . $query . '%']);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($users) > 0) {
            foreach ($users as $user) {
                echo "<p><a href='view_user.php?id=" . $user['id'] . "'>" . htmlspecialchars($user['username']) . "</a></p>";
            }
        } else {
            echo "<p>Brak wyników.</p>";
        }
    } else {
        echo "<p>Nie podano zapytania.</p>";
    }
    ?>
  </div>
</div>
<div style="margin-top: 20px; text-align: center;">
      <button class="link" onclick="window.location.href='profile.php';">Powrót</button>
    </div>  
<script src="assets/js/stars.js"></script>
<script src="assets/js/datetime.js"></script>
<div id="watermark">coded by OsPros | 2025</div>
</body>
</html>

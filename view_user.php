<?php
require_once 'assets/php/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Nieprawidłowy identyfikator użytkownika.");
}

$id = intval($_GET['id']);

// Pobierz dane użytkownika
$stmt = $pdo->prepare("SELECT username, profile_description, profile_image, track_name FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Użytkownik nie istnieje.");
}

// Pobierz linki społecznościowe
$linkStmt = $pdo->prepare("SELECT platform, link FROM social_links WHERE user_id = :id");
$linkStmt->execute(['id' => $id]);
$socialLinks = $linkStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Profil: <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div id="background"></div>
    <div id="datetime"></div>

    <div id="main-box">
        <?php if (!empty($user['profile_image'])): ?>
            <img src="assets/pfp/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Zdjęcie profilowe" class="profile-pic">
        <?php endif; ?>

        <div class="profile-nick"><?php echo htmlspecialchars($user['username']); ?></div>

        <?php if (!empty($user['profile_description'])): ?>
            <div class="profile-desc"><?php echo nl2br(htmlspecialchars($user['profile_description'])); ?></div>
        <?php endif; ?>


        <div id="music-player"> 
        <div class="player-controls">
            <button id="play-pause-btn"><i class="fas fa-play"></i></button>
        <div class="track-info">
          <span id="current-track"><?= htmlspecialchars(basename($user['track_name'])) ?></span>
        </div>
            <input type="range" id="volume-control" min="0" max="1" step="0.01" value="1">
        </div>
            <audio id="audio-player" preload="auto">
            <source src="assets/voice_effects/<?= htmlspecialchars($user['track_name']) ?>" type="audio/mpeg">
        </audio>
    </div>

        <?php if (count($socialLinks) > 0): ?>
            <div id="social-bar">
                <?php foreach ($socialLinks as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['link']); ?>" class="social-icon" target="_blank">
                        <i class="fab fa-<?php echo strtolower($link['platform']); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div style="margin-top: 20px; text-align: center;">
      <button class="link" onclick="window.location.href='search_results.php';">Powrót</button>
    </div>               
    <div id="watermark">coded by OsPros | 2025</div>
    <script src="assets/js/stars.js"></script>
    <script src="assets/js/datetime.js"></script>
    <script src="assets/js/musicPlayer.js"></script>
</body>
</html>

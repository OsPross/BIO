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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($user_data['username']) ?></title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
  
  <div id="background"></div>
  <div id="datetime"></div>
  <?php if ($_SESSION['is_admin'] ?? false): ?>
    <button class="pan"><a href="admin_panel.php">Panel Admina</a></button>
<?php endif; ?>
  <button class="wyl"><a href="assets/php/login.php?logout=true">Wyloguj się</a></button>

  <button class="rule"><a href="ruleta.php">Kostki</a></button>
  <div id="content-wrapper">
    <div id="main-box">
      <?php if (!empty($user_data['profile_image'])): ?>
        <img src="assets/pfp/<?= htmlspecialchars($user_data['profile_image']) ?>" alt="Profile Picture" class="profile-pic">
      <?php else: ?>
        <img src="assets/pfp/profile-picture.png" alt="Default Profile Picture" class="profile-pic">
      <?php endif; ?>

      <h1 class="profile-nick"><?= htmlspecialchars($user_data['username']) ?></h1> 
      <p class="profile-desc">
        <?= htmlspecialchars($user_data['profile_description']) ?>
      </p>

      <div id="view-counter">
        <i class="fas fa-eye"></i> <span id="view-count">0</span>
      </div>
    </div>

    <div id="music-player"> 
    <div class="player-controls">
      <button id="play-pause-btn"><i class="fas fa-play"></i></button>
      <div class="track-info">
        <span id="current-track"><?= htmlspecialchars(basename($user_data['track_name'])) ?></span>
      </div>
      <input type="range" id="volume-control" min="0" max="1" step="0.01" value="1">
    </div>
    <audio id="audio-player" preload="auto">
      <source src="assets/voice_effects/<?= htmlspecialchars($user_data['track_name']) ?>" type="audio/mpeg">
    </audio>
  </div>

    <div id="social-bar">
      <?php if (!empty($user_data['social_links'])): ?>
        <?php foreach ($user_data['social_links'] as $link): ?>
          <a href="<?= htmlspecialchars($link['link']) ?>" class="social-icon">
            <i class="fab fa-<?= strtolower($link['platform']) ?>"></i>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Brak linków społecznościowych.</p>
      <?php endif; ?>
    </div>

    
    <div style="margin-top: 20px; text-align: center;">
      <button class="link" onclick="window.location.href='assets/php/add_social_link.php';">Dodaj link </button>
    </div>
  </div>
 
  <script src="assets/js/title.js"></script> 
  <script src="assets/js/stars.js"></script> 
  <script src="assets/js/counter.js"></script> 
  <script src="assets/js/mouse.js"></script>
  <script src="assets/js/musicPlayer.js"></script> 
  <script src="assets/js/datetime.js"></script>
  <script src="assets/js/typing.js"></script>
  <script>
    window.audioConfig = {
      trackPath: "assets/voice_effects/<?= htmlspecialchars($user_data['track_name']) ?>"
    };
  </script>
  <div id="watermark">coded by OsPros | 2025</div>
</body>
</html>
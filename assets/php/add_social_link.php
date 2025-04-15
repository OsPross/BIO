<?php
session_start();
require 'config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dodanie linku społecznościowego
    if (isset($_POST['add_link'])) {
        $platform = trim($_POST['platform']);
        $link = trim($_POST['link']);
        $allowed_platforms = ['facebook', 'twitter', 'instagram', 'github', 'discord', 'youtube'];

        if (!in_array(strtolower($platform), $allowed_platforms)) {
            $_SESSION['error'] = 'Niepoprawna platforma.';
            header('Location: add_social_link.php');
            exit();
        }

        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            $_SESSION['error'] = 'Niepoprawny format linku.';
            header('Location: add_social_link.php');
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO social_links (user_id, platform, link) VALUES (:user_id, :platform, :link)");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':platform', $platform, PDO::PARAM_STR);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = 'Link został zapisany.';
        header('Location: add_social_link.php');
        exit();
    }

    // Aktualizacja opisu profilu
    if (isset($_POST['update_description'])) {
        $profile_description = trim($_POST['profile_description']);

        if (!empty($profile_description) && strlen($profile_description) <= 200) {
            $stmt = $pdo->prepare("UPDATE users SET profile_description = :profile_description WHERE id = :user_id");
            $stmt->bindParam(':profile_description', $profile_description, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = 'Opis został zapisany.';
        } else {
            $_SESSION['error'] = 'Opis nie może być pusty lub przekraczać 200 znaków.';
        }

        header('Location: add_social_link.php');
        exit();
    }

    // Przesyłanie zdjęcia profilowego
    if (isset($_POST['upload_image']) && isset($_FILES['profile_image'])) {
        $target_dir = "../pfp/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['error'] = 'Dozwolone formaty to JPG, JPEG, PNG i GIF.';
            header('Location: add_social_link.php');
            exit();
        }

        // Pobierz stare zdjęcie użytkownika
        $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $old_image = $stmt->fetchColumn();

        $new_filename = uniqid('img_', true) . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // Usuń stare zdjęcie (jeśli nie domyślne)
            if ($old_image && $old_image !== 'profile-picture.png' && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }

            $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :user_id");
            $stmt->bindParam(':profile_image', $new_filename, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = 'Zdjęcie profilowe zostało przesłane.';
        } else {
            $_SESSION['error'] = 'Wystąpił błąd podczas przesyłania zdjęcia.';
        }

        header('Location: add_social_link.php');
        exit();
    }

    // Przesyłanie lub przypisanie pliku audio
    if (isset($_POST['upload_music']) && isset($_FILES['track_name'])) {
        $target_dir = "../voice_effects/";

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $original_filename = $_FILES["track_name"]["name"];
        $audioFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        $allowed_types = ['mp3', 'wav', 'ogg', 'm4a'];
        $allowed_mime_types = ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/x-m4a'];

        if (!in_array($audioFileType, $allowed_types)) {
            $_SESSION['error'] = 'Dozwolone formaty to MP3, WAV, OGG, M4A.';
            header('Location: add_social_link.php');
            exit();
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES["track_name"]["tmp_name"]);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_mime_types)) {
            $_SESSION['error'] = 'Nieprawidłowy typ pliku audio.';
            header('Location: add_social_link.php');
            exit();
        }

        $max_size = 20 * 1024 * 1024;
        if ($_FILES["track_name"]["size"] > $max_size) {
            $_SESSION['error'] = 'Plik jest zbyt duży. Maksymalny rozmiar to 20MB.';
            header('Location: add_social_link.php');
            exit();
        }

        $safe_filename = preg_replace("/[^a-zA-Z0-9._-]/", "_", pathinfo($original_filename, PATHINFO_FILENAME));
        $new_filename = $safe_filename . '.' . $audioFileType;
        $target_file = $target_dir . $new_filename;

        if (file_exists($target_file)) {
            // Plik już istnieje — przypisz bez ponownego wrzucania
            $stmt = $pdo->prepare("UPDATE users SET track_name = :track_name WHERE id = :user_id");
            $stmt->bindParam(':track_name', $new_filename, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = 'Plik "' . htmlspecialchars($original_filename) . '" już istnieje – przypisano do twojego konta.';
        } else {
            if (move_uploaded_file($_FILES["track_name"]["tmp_name"], $target_file)) {
                $stmt = $pdo->prepare("UPDATE users SET track_name = :track_name WHERE id = :user_id");
                $stmt->bindParam(':track_name', $new_filename, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['success'] = 'Plik audio "' . htmlspecialchars($original_filename) . '" został przesłany.';
            } else {
                $_SESSION['error'] = 'Wystąpił błąd podczas przesyłania pliku audio.';
            }
        }

        header('Location: add_social_link.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj link społecznościowy</title>
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div id="background"></div>
    <div id="datetime"></div>

    <div id="content-wrapper">
        <div id="main-box">
            <h1 class="profile-nick">Dodaj link społecznościowy, edytuj opis i prześlij pliki</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="add_social_link.php" method="POST">
                <label for="platform" class="form-label">Platforma</label>
                <select name="platform" id="platform" required>
                    <option value="facebook">Facebook</option>
                    <option value="twitter">Twitter</option>
                    <option value="instagram">Instagram</option>
                    <option value="github">GitHub</option>
                    <option value="discord">Discord</option>
                    <option value="youtube">YouTube</option>
                </select>
                
                <label for="link" class="form-label">Link</label>
                <input type="url" name="link" id="link" placeholder="https://example.com" required>
                
                <button type="submit" name="add_link" class="submit-btn">Dodaj link</button>
            </form>

            <form id="profile-image-form" action="add_social_link.php" method="POST" enctype="multipart/form-data" class="upload-form">
                <h3 class="section-title">Zdjęcie profilowe</h3>
                
                <div class="file-upload-container">
                    <div class="file-upload-wrapper">
                        <input type="file" name="profile_image" id="profile_image" class="file-input" accept="image/jpeg,image/png" required>
                        
                        <div class="file-upload-box">
                            <div class="file-upload-header">
                                <span class="file-upload-main-text">Przeciągnij i upuść zdjęcie lub</span>
                            </div>
                            
                            <button type="button" class="browse-btn">Wybierz plik</button>
                            
                            <div class="file-upload-footer">
                                <span class="file-upload-hint">Obsługiwane formaty: JPG, JPEG, PNG</span>
                                <span id="profile-file-status" class="file-status">Nie wybrano pliku</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="upload_image" class="submit-btn">
                    <i class="fas fa-upload"></i> Prześlij zdjęcie
                </button>
            </form>

            <form id="music-upload-form" action="add_social_link.php" method="POST" enctype="multipart/form-data" class="upload-form">
                <h3 class="section-title">Plik audio</h3>
                
                <div class="file-upload-container">
                    <div class="file-upload-wrapper">
                        <input type="file" name="track_name" id="track_name" class="file-input" accept="audio/*" required>
                        
                        <div class="file-upload-box">
                            <div class="file-upload-header">
                                <span class="file-upload-main-text">Przeciągnij i upuść plik audio lub</span>
                            </div>
                            
                            <button type="button" class="browse-btn">Wybierz plik</button>
                            
                            <div class="file-upload-footer">
                                <span class="file-upload-hint">Obsługiwane formaty: MP3, WAV, OGG, M4A (max 20MB)</span>
                                <span id="audio-file-status" class="file-status">Nie wybrano pliku</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="upload_music" class="submit-btn">
                    <i class="fas fa-music"></i> Prześlij plik audio
                </button>
            </form>

            <a href="../../profile.php" class="back-btn">Wróć do profilu</a>
        </div>
    </div>

    <script src="../js/datetime.js"></script>
    <script src="../js/stars.js"></script>
    <script src="../js/file-upload.js"></script>

    <div id="watermark">coded by OsPros | 2025</div>
</body>
</html>
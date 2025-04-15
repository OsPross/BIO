<?php
session_start();
require 'assets/php/config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: ../../index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !$user['is_admin']) {
    header('Location: ../../profile.php');
    exit();
}

$edit_user_id = $_GET['id'] ?? 0;
$edit_user = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$edit_user->bindParam(':user_id', $edit_user_id, PDO::PARAM_INT);
$edit_user->execute();
$user_data = $edit_user->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    $_SESSION['admin_error'] = "Użytkownik nie istnieje.";
    header('Location: admin_panel.php');
    exit();
}

$social_links = $pdo->prepare("SELECT * FROM social_links WHERE user_id = :user_id");
$social_links->bindParam(':user_id', $edit_user_id, PDO::PARAM_INT);
$social_links->execute();
$links = $social_links->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $username = trim($_POST['username']);
        $profile_description = trim($_POST['profile_description']);
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND id != :user_id");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $edit_user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['admin_error'] = "Nazwa użytkownika jest już zajęta.";
            header("Location: admin_edit_user.php?id=" . $edit_user_id);
            exit();
        }
        
        $stmt = $pdo->prepare("UPDATE users SET username = :username, profile_description = :profile_description, is_admin = :is_admin WHERE id = :user_id");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':profile_description', $profile_description, PDO::PARAM_STR);
        $stmt->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $edit_user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $_SESSION['admin_message'] = "Dane użytkownika zostały zaktualizowane.";
        header("Location: admin_edit_user.php?id=" . $edit_user_id);
        exit();
    }
    
    if (isset($_POST['delete_link'])) {
        $link_id = $_POST['link_id'];
        
        $stmt = $pdo->prepare("DELETE FROM social_links WHERE id = :link_id AND user_id = :user_id");
        $stmt->bindParam(':link_id', $link_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $edit_user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $_SESSION['admin_message'] = "Link został usunięty.";
        header("Location: admin_edit_user.php?id=" . $edit_user_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja użytkownika - Panel Administratora</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="background"></div>
    <div id="datetime"></div>
    
    <div id="content-wrapper">
        <div id="main-box">
            <h1 class="profile-nick"><i class="fas fa-user-edit"></i> Edycja użytkownika: <?= htmlspecialchars($user_data['username']) ?></h1>
            
            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['admin_message']) ?></div>
                <?php unset($_SESSION['admin_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['admin_error']) ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>
            
            <form method="POST" class="admin-form">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="profile_description">Opis profilu:</label>
                    <textarea id="profile_description" name="profile_description"><?= htmlspecialchars($user_data['profile_description']) ?></textarea>
                </div>
                
                <div class="form-group checkbox">
                    <input type="checkbox" id="is_admin" name="is_admin" <?= $user_data['is_admin'] ? 'checked' : '' ?>>
                    <label for="is_admin">Administrator</label>
                </div>
                
                <button type="submit" name="update_user" class="log"><i class="fas fa-save"></i> Zapisz zmiany</button>
            </form>
            
            <div class="user-info">
                <h3><i class="fas fa-info-circle"></i> Informacje dodatkowe</h3>
                <p><strong>ID:</strong> <?= htmlspecialchars($user_data['id']) ?></p>
                <p><strong>Data rejestracji:</strong> <?= date('Y-m-d H:i', strtotime($user_data['created_at'])) ?></p>
                <p><strong>Zdjęcie profilowe:</strong> <?= htmlspecialchars($user_data['profile_image']) ?></p>
                <p><strong>Plik audio:</strong> <?= htmlspecialchars($user_data['track_name']) ?></p>
            </div>
            
            <div class="social-links">
                <h3><i class="fas fa-share-alt"></i> Linki społecznościowe</h3>
                
                <?php if (count($links) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Platforma</th>
                                <th>Link</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($links as $link): ?>
                            <tr>
                                <td><?= htmlspecialchars(ucfirst($link['platform'])) ?></td>
                                <td><a href="<?= htmlspecialchars($link['link']) ?>" target="_blank"><?= htmlspecialchars($link['link']) ?></a></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć ten link?');">
                                        <input type="hidden" name="link_id" value="<?= $link['id'] ?>">
                                        <button type="submit" name="delete_link" class="log delete small"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Użytkownik nie ma jeszcze dodanych linków społecznościowych.</p>
                <?php endif; ?>
            </div>
            
            <a href="admin_panel.php" class="log"><i class="fas fa-arrow-left"></i> Powrót do panelu</a>
        </div>
    </div>
    
    <div id="watermark">coded by OsPros | 2025</div>
    
    <script src="assets/js/stars.js"></script>
    <script src="assets/js/datetime.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('fade-in');
        });
    </script>
</body>
</html>
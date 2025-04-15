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
    header('Location: /profile.php');
    exit();
}

$users = $pdo->query("SELECT id, username, is_admin, created_at FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_admin'])) {
        $user_id = $_POST['user_id'];
        $is_admin = $_POST['is_admin'] ? 0 : 1;
        
        $stmt = $pdo->prepare("UPDATE users SET is_admin = :is_admin WHERE id = :user_id");
        $stmt->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        header("Location: admin_panel.php");
        exit();
    }
    
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        
        $pdo->beginTransaction();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM social_links WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $pdo->commit();
            $_SESSION['admin_message'] = "Użytkownik został pomyślnie usunięty.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['admin_error'] = "Błąd podczas usuwania użytkownika: " . $e->getMessage();
        }
        
        header("Location: admin_panel.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="background"></div>
    <div id="datetime"></div>
    
    <div id="content-wrapper">
        <div id="main-box">
            <h1 class="profile-nick"><i class="fas fa-crown"></i> Panel Administratora</h1>
            
            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['admin_message']) ?></div>
                <?php unset($_SESSION['admin_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['admin_error']) ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>
            
            <div class="stats">
                <div class="stat-card">
                    <h3>Liczba użytkowników</h3>
                    <p><?= count($users) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Administratorzy</h3>
                    <p><?= array_reduce($users, function($carry, $user) { return $carry + ($user['is_admin'] ? 1 : 0); }, 0) ?></p>
                </div>
            </div>
            
            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa użytkownika</th>
                            <th>Data rejestracji</th>
                            <th>Status</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?php if ($user['is_admin']): ?>
                                    <span class="badge admin"><i class="fas fa-crown"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge user"><i class="fas fa-user"></i> Użytkownik</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="is_admin" value="<?= $user['is_admin'] ?>">
                                    <button type="submit" name="toggle_admin" class="log <?= $user['is_admin'] ? 'revoke' : 'grant' ?>">
                                        <?= $user['is_admin'] ? 'Odbierz uprawnienia' : 'Nadaj uprawnienia' ?>
                                    </button>
                                </form>
                                
                                <a href="admin_edit_user.php?id=<?= $user['id'] ?>" class="log edit">
                                    <i class="fas fa-edit"></i> Edytuj
                                </a>
                                
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete_user" class="log delete">
                                        <i class="fas fa-trash"></i> Usuń
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <a href="profile.php" class="log"><i class="fas fa-arrow-left"></i> Powrót do profilu</a>
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
<?php
require 'config.php';

function getUserData($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT username, profile_description, profile_image, track_name FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT platform, link FROM social_links WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $social_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'username' => $user['username'],
        'profile_description' => $user['profile_description'],
        'profile_image' => $user['profile_image'],
        'social_links' => $social_links,
        'track_name' => $user['track_name'] ?? 'default_track.mp3' // Pobieramy z tabeli users
    ];
}
?>
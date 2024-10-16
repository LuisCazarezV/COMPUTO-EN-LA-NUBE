<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Obtener la ruta de la foto de perfil
    $stmt = $conn->prepare("SELECT profile_photo_url FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile_photo_url = $stmt->fetchColumn();

    // Eliminar la foto de perfil si existe
    if ($profile_photo_url && file_exists($profile_photo_url)) {
        unlink($profile_photo_url); // Eliminar el archivo
    }

    // Actualizar la base de datos
    $stmt = $conn->prepare("UPDATE users SET profile_photo_url = NULL WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    header("Location: ../perfil.html?deleted=1");
}
?>

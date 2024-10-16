<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    $user_id = $_SESSION['user_id'];
    $file_name = "profile_" . $user_id . ".jpg";
    $target_dir = "../Images/users/";
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
        // Actualizar la base de datos con la ruta de la foto
        $stmt = $conn->prepare("UPDATE users SET profile_photo_url = ? WHERE user_id = ?");
        $stmt->execute([$target_file, $user_id]);
        header("Location: ../perfil.html?success=1");
    } else {
        header("Location: ../perfil.html?error=upload_failed");
    }
}
?>

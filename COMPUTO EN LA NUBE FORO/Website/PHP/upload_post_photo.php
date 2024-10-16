<?php
session_start();
require 'db.php'; // Asegúrate de tener la conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['post_photo']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario actual desde la sesión.
    $post_id = $_POST['post_id']; // ID del post al que se asocia la imagen.
    $file_name = "post_" . $post_id . "_" . time() . ".jpg"; // Crear un nombre único para la imagen.
    $target_dir = "../Images/posts/"; // Directorio donde se almacenarán las imágenes de los posts.
    $target_file = $target_dir . $file_name;

    // Verificar si el archivo subido es una imagen.
    $check = getimagesize($_FILES['post_photo']['tmp_name']);
    if ($check !== false) {
        // Mover el archivo subido al directorio deseado.
        if (move_uploaded_file($_FILES['post_photo']['tmp_name'], $target_file)) {
            // Guardar la URL de la imagen en la base de datos.
            $stmt = $conn->prepare("INSERT INTO photos (user_id, post_id, photo_url) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $post_id, $target_file]);

            // Redirigir al post con un mensaje de éxito.
            header("Location: ../post.php?id=" . $post_id . "&success=photo_uploaded");
        } else {
            header("Location: ../post.php?id=" . $post_id . "&error=upload_failed");
        }
    } else {
        header("Location: ../post.php?id=" . $post_id . "&error=not_image");
    }
} else {
    header("Location: ../index.html?error=invalid_request");
}
?>

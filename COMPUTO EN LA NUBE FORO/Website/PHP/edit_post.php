<?php
session_start();
require 'db.php'; // Conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión.
    $post_content = $_POST['post_content']; // Obtener el contenido actualizado.

    try {
        // Iniciar la transacción.
        $conn->beginTransaction();

        // Actualizar el contenido del post en la tabla `posts`.
        $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_content, $post_id, $user_id]);

        // Si se subió una nueva imagen, procesarla.
        if (isset($_FILES['post_photo']) && $_FILES['post_photo']['size'] > 0) {
            $file_name = "post_" . $post_id . "_" . time() . ".jpg"; // Crear un nombre único para la imagen.
            $target_dir = "../Images/posts/"; // Directorio donde se almacenarán las imágenes de los posts.
            $target_file = $target_dir . $file_name;

            // Verificar si el archivo subido es una imagen.
            $check = getimagesize($_FILES['post_photo']['tmp_name']);
            if ($check !== false) {
                // Mover el archivo subido al directorio deseado.
                if (move_uploaded_file($_FILES['post_photo']['tmp_name'], $target_file)) {
                    // Actualizar la ruta de la imagen en la tabla `photos`.
                    $stmt = $conn->prepare("UPDATE photos SET photo_url = ? WHERE post_id = ?");
                    $stmt->execute([$target_file, $post_id]);
                } else {
                    throw new Exception("Error al subir la imagen.");
                }
            } else {
                throw new Exception("El archivo subido no es una imagen.");
            }
        }

        // Confirmar la transacción.
        $conn->commit();
        header("Location: ../foro.html?success=post_updated");
    } catch (Exception $e) {
        // En caso de error, deshacer la transacción.
        $conn->rollBack();
        header("Location: ../edit_post.php?post_id=" . $post_id . "&error=" . $e->getMessage());
    }
} else {
    header("Location: ../edit_post.php?post_id=" . $post_id . "&error=invalid_request");
}
?>

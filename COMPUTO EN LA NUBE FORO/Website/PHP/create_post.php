<?php
session_start();
require 'db.php'; // Conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión.
    $post_content = $_POST['post_content']; // Obtener el contenido del post desde el formulario.

    try {
        // Iniciar la transacción.
        $conn->beginTransaction();

        // Insertar el contenido del post en la tabla `posts`.
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->execute([$user_id, $post_content]);

        // Obtener el ID del nuevo post.
        $post_id = $conn->lastInsertId();

        // Si se subió una imagen, procesarla.
        if (isset($_FILES['post_photo']) && $_FILES['post_photo']['size'] > 0) {
            $file_name = "post_" . $post_id . "_" . time() . ".jpg"; // Crear un nombre único para la imagen.
            $target_dir = "../Images/posts/"; // Directorio donde se almacenarán las imágenes de los posts.
            $target_file = $target_dir . $file_name;

            // Verificar si el archivo subido es una imagen.
            $check = getimagesize($_FILES['post_photo']['tmp_name']);
            if ($check !== false) {
                // Mover el archivo subido al directorio deseado.
                if (move_uploaded_file($_FILES['post_photo']['tmp_name'], $target_file)) {
                    // Guardar la URL de la imagen en la tabla `photos`.
                    $stmt = $conn->prepare("INSERT INTO photos (user_id, post_id, photo_url) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $post_id, $target_file]);
                } else {
                    throw new Exception("Error al subir la imagen.");
                }
            } else {
                throw new Exception("El archivo subido no es una imagen.");
            }
        }

        // Confirmar la transacción.
        $conn->commit();
        header("Location: ../foro.html?success=post_created");
    } catch (Exception $e) {
        // En caso de error, deshacer la transacción.
        $conn->rollBack();
        header("Location: ../create_post.html?error=" . $e->getMessage());
    }
} else {
    header("Location: ../create_post.html?error=invalid_request");
}
?>

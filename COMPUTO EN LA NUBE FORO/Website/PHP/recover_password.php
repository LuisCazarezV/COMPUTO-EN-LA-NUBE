<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Simulación de envío de correo de recuperación
        echo "Instrucciones para restablecer la contraseña enviadas a $email.";
    } else {
        echo "El correo no está registrado.";
    }
}
?>

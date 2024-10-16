<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Tu nombre de usuario es: " . $user['username'];
    } else {
        echo "No se encontró ninguna cuenta con ese correo.";
    }
}
?>

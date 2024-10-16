<?php
session_start();
require 'db.php'; // Incluir el archivo de conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: ../register.html?error=password_mismatch");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Si el correo ya existe
        header("Location: ../register.html?error=email_taken");
        exit();
    }

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);

    // Establecer la sesión del usuario
    $_SESSION['user_id'] = $conn->lastInsertId();
    $_SESSION['username'] = $username;

    header("Location: ../perfil.html");
}
?>


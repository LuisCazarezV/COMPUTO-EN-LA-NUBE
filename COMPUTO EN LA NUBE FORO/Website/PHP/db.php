<?php
// db.php: Archivo para establecer la conexión con la base de datos SQLite.

try {
    // Conectar a la base de datos SQLite
    $conn = new PDO('sqlite:./DataBase/DataBase.db');
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejo de errores
    echo "Conexión fallida: " . $e->getMessage();
}
?>

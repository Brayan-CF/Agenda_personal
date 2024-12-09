<?php
$host = 'localhost';
$dbname = 'agenda_personal';
$username = 'root';  // Usuario de XAMPP
$password = '';      // Contraseña de XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

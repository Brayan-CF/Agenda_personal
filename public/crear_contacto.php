<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../src/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_contacto = $_POST['tipo_contacto'];
    $usuario_id = $_SESSION['usuario_id']; // Asociar al usuario autenticado

    if (!empty($nombre) && !empty($telefono) && !empty($correo) && !empty($tipo_contacto)) {
        $stmt = $pdo->prepare("INSERT INTO contactos (nombre, telefono, correo, tipo_contacto, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $telefono, $correo, $tipo_contacto, $usuario_id]);
        header("Location: index.php");
        exit;
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>

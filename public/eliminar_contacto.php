<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "<h1>No has iniciado sesión.</h1>";
    echo '<a href="../auth/login.php">Inicia sesión aquí</a>';
    exit();
}

require '../src/config/database.php';

$usuario_id = $_SESSION['usuario_id'];

// Verificar si el ID de contacto está presente
if (!isset($_GET['id'])) {
    echo "<h1>Contacto no encontrado.</h1>";
    exit();
}

$contacto_id = $_GET['id'];

// Eliminar el contacto de la base de datos
$stmt = $pdo->prepare("DELETE FROM contactos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$contacto_id, $usuario_id]);

echo "<h2>Contacto eliminado con éxito.</h2>";
echo '<a href="index.php">Volver a la lista de contactos</a>';
exit();
?>

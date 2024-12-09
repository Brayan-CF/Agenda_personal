<?php
// Incluir archivos de configuración y lógica (si es necesario)
require_once '../src/config/database.php';

// Comprobar si el usuario está autenticado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consultar los contactos del usuario
$stmt = $pdo->prepare("SELECT * FROM contactos WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir el encabezado del archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="contactos.csv"');

// Abrir el archivo para escritura en memoria
$output = fopen('php://output', 'w');

// Escribir los encabezados en el CSV
fputcsv($output, ['ID', 'Nombre', 'Teléfono', 'Correo', 'Tipo de contacto']);

// Escribir los datos de cada contacto
foreach ($contactos as $contacto) {
    fputcsv($output, $contacto);
}

// Cerrar el archivo
fclose($output);
exit();
?>

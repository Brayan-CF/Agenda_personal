<?php
// Incluir archivos de configuración y lógica (si es necesario)
require_once '../src/config/database.php';

// Comprobar si el usuario está autenticado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo'])) {
    // Verificar que el archivo cargado es un archivo CSV
    $file = $_FILES['archivo'];

    if ($file['type'] != 'text/csv') {
        echo "Por favor, sube un archivo CSV.";
        exit();
    }

    // Abrir el archivo CSV
    $fileTmp = fopen($file['tmp_name'], 'r');

    // Saltar la primera línea (encabezados)
    fgetcsv($fileTmp);

    // Insertar los contactos en la base de datos
    $usuario_id = $_SESSION['usuario_id'];
    while (($data = fgetcsv($fileTmp)) !== false) {
        // Insertar cada fila del CSV como un contacto
        $stmt = $pdo->prepare("INSERT INTO contactos (nombre, telefono, correo, tipo_contacto, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data[1], $data[2], $data[3], $data[4], $usuario_id]);
    }

    fclose($fileTmp);
    echo "Contactos importados correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Importar Contactos</title>
</head>
<body>
    <h2>Importar Contactos</h2>
    <form action="importar_contactos.php" method="POST" enctype="multipart/form-data">
        <label for="archivo">Seleccionar archivo CSV</label>
        <input type="file" name="archivo" id="archivo" required>
        <button type="submit">Subir</button>
    </form>
</body>
</html>

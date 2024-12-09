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

// Obtener los datos del contacto a editar
$stmt = $pdo->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$contacto_id, $usuario_id]);
$contacto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contacto) {
    echo "<h1>El contacto no existe o no pertenece a tu cuenta.</h1>";
    exit();
}

// Verificar si el formulario ha sido enviado para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_contacto = $_POST['tipo_contacto'];

    // Actualizar los datos en la base de datos
    $stmt = $pdo->prepare("UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, tipo_contacto = ? WHERE id = ?");
    $stmt->execute([$nombre, $telefono, $correo, $tipo_contacto, $contacto_id]);

    echo "<h2>Contacto actualizado con éxito.</h2>";
    echo '<a href="index.php">Volver a la lista de contactos</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h1>Editar Contacto</h1>
        <form action="editar_contacto.php?id=<?= $contacto['id'] ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($contacto['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($contacto['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($contacto['correo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_contacto" class="form-label">Tipo de contacto</label>
                <select id="tipo_contacto" name="tipo_contacto" class="form-select">
                    <option value="Personal" <?= $contacto['tipo_contacto'] === 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Trabajo" <?= $contacto['tipo_contacto'] === 'Trabajo' ? 'selected' : '' ?>>Trabajo</option>
                    <option value="Otro" <?= $contacto['tipo_contacto'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
        </form>
    </div>
</body>

</html>
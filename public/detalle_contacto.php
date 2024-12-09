<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../src/config/database.php';
// soy un comentario

if (isset($_GET['id'])) {
    $contacto_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$contacto_id, $_SESSION['usuario_id']]);
    $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contacto) {
        echo "<h1>Contacto no encontrado</h1>";
        exit();
    }
} else {
    echo "<h1>Contacto no especificado</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles de: <?= htmlspecialchars($contacto['nombre']) ?></h1>

        <!-- Tabla de detalles del contacto -->
        <table class="table table-striped">
            <tr>
                <th>Nombre</th>
                <td><?= htmlspecialchars($contacto['nombre']) ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?= htmlspecialchars($contacto['telefono']) ?></td>
            </tr>
            <tr>
                <th>Correo</th>
                <td><?= htmlspecialchars($contacto['correo']) ?></td>
            </tr>
            <tr>
                <th>Tipo de contacto</th>
                <td><?= htmlspecialchars($contacto['tipo_contacto']) ?></td>
            </tr>
        </table>

        <!-- Botones de acción -->
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Volver</a>
            <a href="editar_contacto.php?id=<?= $contacto['id'] ?>" class="btn btn-primary">Editar</a>
            <form action="eliminar_contacto.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?= $contacto['id'] ?>">
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este contacto?')">Eliminar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

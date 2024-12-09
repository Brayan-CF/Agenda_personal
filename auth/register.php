<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../src/config/database.php';
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (correo, contrasena) VALUES (?, ?)");
    try {
        $stmt->execute([$correo, $contrasena]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        echo "Error al registrar el usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Crear Cuenta</h2>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
        </form>
    </div>
</body>
</html>
<?php
require_once __DIR__ . '/config/database.php';

try {
    $contactos = [];

    // Procesar la búsqueda AJAX
    if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
        if (isset($_GET['autocompletar'])) {
            $buscar = $_GET['autocompletar'] ?? '';
            $buscar = '%' . $buscar . '%';

            $stmt = $pdo->prepare("SELECT id, nombre FROM contactos WHERE nombre LIKE ? LIMIT 5");
            $stmt->execute([$buscar]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($resultados);
            exit();
        }

        $buscar = $_GET['buscar'] ?? '';
        $tipoContacto = $_GET['tipo_contacto'] ?? '';

        $sql = "SELECT id, nombre FROM contactos WHERE nombre LIKE ?";
        $params = ['%' . $buscar . '%'];

        if (!empty($tipoContacto)) {
            $sql .= " AND tipo_contacto = ?";
            $params[] = $tipoContacto;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit();
    }

    // Acción de editar contacto
    if (isset($_GET['accion']) && $_GET['accion'] === 'editar') {
        $id = $_GET['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $tipo_contacto = $_POST['tipo_contacto'] ?? null;

        if ($id && $nombre && $telefono && $correo && $tipo_contacto) {
            $stmt = $pdo->prepare("UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, tipo_contacto = ? WHERE id = ?");
            $stmt->execute([$nombre, $telefono, $correo, $tipo_contacto, $id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit();
    }

    // Acción de eliminar contacto
    if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM contactos WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit();
    }

    // Cargar contactos iniciales para la lista principal
    if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
        $buscar = '%' . $_GET['buscar'] . '%';
        $stmt = $pdo->prepare("SELECT id, nombre, telefono, correo, tipo_contacto FROM contactos WHERE nombre LIKE ?");
        $stmt->execute([$buscar]);
        $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = $pdo->query("SELECT id, nombre, telefono, correo, tipo_contacto FROM contactos");
        $contactos = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si se ha solicitado un contacto específico
    $contactoSeleccionado = null;
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT id, nombre, telefono, correo, tipo_contacto FROM contactos WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $contactoSeleccionado = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Procesar formulario de creación de contacto
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $tipo_contacto = $_POST['tipo_contacto'] ?? 'Personal';

        if ($nombre && $telefono && $correo && $tipo_contacto) {
            $stmt = $pdo->prepare("INSERT INTO contactos (nombre, telefono, correo, tipo_contacto) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $telefono, $correo, $tipo_contacto]);

            header("Location: index.php");
            exit();
        } else {
            echo "Por favor, completa todos los campos obligatorios.";
        }
    }
} catch (PDOException $e) {
    die("Error al obtener los contactos: " . $e->getMessage());
}
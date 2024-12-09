<?php
session_start();
// esto es un comneario de mi, si de mi Brayan :v 
if (!isset($_SESSION['usuario_id'])) {
    echo "<h1>No has iniciado sesión.</h1>";
    echo '<a href="../auth/login.php">Inicia sesión aquí</a>';
    exit();
}

require '../src/config/database.php';

// Obtener el usuario autenticado
$usuario_id = $_SESSION['usuario_id'];

// Consultar contactos del usuario autenticado
$stmt = $pdo->prepare("SELECT * FROM contactos WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($contactos);
//exit;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Personal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Agenda Personal</a>
            <div class="d-flex ms-auto">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../auth/logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn btn-outline-primary me-2">Iniciar Sesión</a>
                    <a href="../auth/register.php" class="btn btn-primary">Crear Cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <h1></h1>

        <!-- Botón para mostrar el formulario de crear contacto -->
        <div class="text-start mb-3">
            <button id="btn-mostrar-form" class="btn btn-primary">+ Añadir</button>
        </div>

        <div class="row">
            <!-- Formulario para crear un nuevo contacto (oculto inicialmente) -->
            <div id="form-crear-contacto" class="mt-4 d-none">
                <h2>Crear un nuevo contacto</h2>
                <form action="crear_contacto.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_contacto">Tipo de contacto</label>
                        <select name="tipo_contacto" id="tipo_contacto" class="form-control">
                            <option value="Personal">Personal</option>
                            <option value="Trabajo">Trabajo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Contacto</button>
                </form>
            </div>
            <!-- Lista de contactos -->
            <div class="col-md-4">
                <!-- Campo de búsqueda -->
                <form id="form-busqueda-avanzada" class="mb-3">
                    <div class="position-relative">
                        <input
                            type="text"
                            id="buscar"
                            name="buscar"
                            class="form-control mb-2"
                            placeholder="Buscar contacto..."
                            autocomplete="off">
                        <div id="sugerencias" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                    </div>
                    <select id="tipo_contacto" name="tipo_contacto" class="form-control mb-2">
                        <option value="">Todos los tipos</option>
                        <option value="Personal">Personal</option>
                        <option value="Trabajo">Trabajo</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
                <ul id="lista-contactos" class="list-group"></ul>

                <a href="exportar_contactos.php" class="btn btn-success">Exportar Contactos</a>
                <a href="importar_contactos.php" class="btn btn-warning">Importar Contactos</a>

            </div>
            <!-- Detalles del contacto seleccionado -->
            <div class="col-md-8">
                <div id="alerta" class="alert d-none" role="alert"></div>
                <?php if (!empty($contactoSeleccionado)): ?>
                    <h2>Detalles de <?= htmlspecialchars($contactoSeleccionado['nombre']); ?></h2>
                    <form id="form-editar" class="mb-3" action="router.php?accion=editar&id=<?= htmlspecialchars($contactoSeleccionado['id']); ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($contactoSeleccionado['nombre']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($contactoSeleccionado['telefono']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($contactoSeleccionado['correo']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_contacto" class="form-label">Tipo de contacto</label>
                            <select id="tipo_contacto" name="tipo_contacto" class="form-select">
                                <option value="Personal" <?= $contactoSeleccionado['tipo_contacto'] === 'Personal' ? 'selected' : '' ?>>Personal</option>
                                <option value="Trabajo" <?= $contactoSeleccionado['tipo_contacto'] === 'Trabajo' ? 'selected' : '' ?>>Trabajo</option>
                                <option value="Otro" <?= $contactoSeleccionado['tipo_contacto'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </form>
                    <!-- Formulario para eliminar el contacto -->
                    <form action="router.php?accion=eliminar&id=<?= htmlspecialchars($contactoSeleccionado['id']); ?>" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-danger">Eliminar Contacto</button>
                    </form>
                <?php else: ?>
                    <p>Selecciona un contacto para ver los detalles.</p>
                <?php endif; ?>
            </div>

        </div>

        <?php if (!empty($contactos)): ?>
            <?php foreach ($contactos as $contacto): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($contacto['nombre']) ?></h5>
                        <p class="card-text">Teléfono: <?= htmlspecialchars($contacto['telefono']) ?></p>
                        <p class="card-text">Correo: <?= htmlspecialchars($contacto['correo']) ?></p>
                        <p class="card-text">Tipo de contacto: <?= htmlspecialchars($contacto['tipo_contacto']) ?></p> <!-- Aquí agregamos el tipo de contacto -->
                        <a href="editar_contacto.php?id=<?= $contacto['id'] ?>" class="btn btn-primary">Editar</a>
                        <a href="eliminar_contacto.php?id=<?= $contacto['id'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>


        <?php else: ?>
            <p>No hay contactos disponibles.</p>
        <?php endif; ?>

    </div>
    


    <div id="spinner" class="d-none text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visualmente-oculto">Cargando...</span>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        document.getElementById('form-busqueda-avanzada').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario

            const query = document.getElementById('buscar').value;
            const tipoContacto = document.getElementById('tipo_contacto').value;

            mostrarSpinner();
            // Realiza la petición AJAX
            fetch(`../src/routes.php?ajax=1&buscar=${encodeURIComponent(query)}&tipo_contacto=${encodeURIComponent(tipoContacto)}`)
                .then(response => response.json())
                .then(data => {
                    const listaContactos = document.getElementById('lista-contactos');
                    listaContactos.innerHTML = ''; // Limpiar la lista

                    if (data.length > 0) {
                        data.forEach(contacto => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.innerHTML = `<a href="detalle_contacto.php?id=${contacto.id}">${contacto.nombre}</a>`;
                            listaContactos.appendChild(li);
                        });
                    } else {
                        listaContactos.innerHTML = '<li class="list-group-item">No hay contactos disponibles.</li>';
                    }
                })
                .catch(error => console.error('Error en la solicitud:', error))
                .finally(() => {
                    ocultarSpinner();
                });
        });

        document.getElementById('buscar').addEventListener('input', function() {
            const query = this.value;

            if (query.length < 2) {
                document.getElementById('sugerencias').innerHTML = ''; // Limpia las sugerencias si el texto es corto
                return;
            }

            mostrarSpinner();
            // Realiza la petición AJAX para obtener las sugerencias
            fetch(`../src/routes.php?ajax=1&autocompletar=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const sugerencias = document.getElementById('sugerencias');
                    sugerencias.innerHTML = ''; // Limpia las sugerencias existentes

                    if (data.length > 0) {
                        data.forEach(contacto => {
                            const item = document.createElement('a');
                            item.href = `detalle_contacto.php?id=${contacto.id}`;
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = contacto.nombre;

                            sugerencias.appendChild(item);
                        });
                    } else {
                        sugerencias.innerHTML = '<div class="list-group-item">Sin resultados</div>';
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    ocultarSpinner();
                });
        });

        function mostrarMensaje(mensaje, tipo = 'success') {
            const alerta = document.getElementById('alerta');
            alerta.className = `alert alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.classList.remove('d-none');

            setTimeout(() => {
                alerta.classList.add('d-none');
            }, 3000); // Oculta la alerta después de 3 segundos
        }

        document.getElementById('form-editar')?.addEventListener('submit', function(event) {
            event.preventDefault();

            const idContacto = new URLSearchParams(window.location.search).get('id');
            const formData = new FormData(this);

            mostrarSpinner();
            fetch(`../src/routes.php?accion=editar&id=${idContacto}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Contacto actualizado con éxito.');
                        location.reload(); // Recargar la página para actualizar los detalles
                    } else {
                        toastr.error('Error al actualizar el contacto.');
                    }
                })
                .catch(error => {
                    console.error('Error al editar:', error);
                    toastr.error('Error al editar el contacto.');
                })
                .finally(() => {
                    ocultarSpinner();
                });
        });

        document.getElementById('btn-eliminar')?.addEventListener('click', function() {
            if (!confirm('¿Estás seguro de que deseas eliminar este contacto?')) return;

            const idContacto = new URLSearchParams(window.location.search).get('id');

            mostrarSpinner();
            fetch(`../src/routes.php?accion=eliminar&id=${idContacto}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Contacto eliminado con éxito.');
                        window.location.href = 'index.php'; // Redirigir a la lista principal
                    } else {
                        toastr.error('Error al eliminar el contacto.');
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar:', error);
                    toastr.error('Error al eliminar el contacto.');
                })
                .finally(() => {
                    ocultarSpinner();
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            fetch('../src/routes.php?ajax=1&buscar=')
                .then(response => {
                    console.log('Respuesta de la solicitud:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);
                    const listaContactos = document.getElementById('lista-contactos');
                    listaContactos.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(contacto => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.innerHTML = `<a href="?id=${contacto.id}">${contacto.nombre}</a>`;
                            listaContactos.appendChild(li);
                        });
                    } else {
                        listaContactos.innerHTML = '<li class="list-group-item">No hay contactos disponibles.</li>';
                    }
                })
                .catch(error => console.error('Error inicial:', error));
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
        };

        function mostrarSpinner() {
            document.getElementById('spinner').classList.remove('d-none');
        }

        function ocultarSpinner() {
            document.getElementById('spinner').classList.add('d-none');
        }

        // Mostrar y ocultar el formulario de crear contacto
        document.getElementById('btn-mostrar-form').addEventListener('click', function() {
            const formCrearContacto = document.getElementById('form-crear-contacto');
            if (formCrearContacto.classList.contains('d-none')) {
                formCrearContacto.classList.remove('d-none');
            } else {
                formCrearContacto.classList.add('d-none');
            }
        });
    </script>
</body>

</html>
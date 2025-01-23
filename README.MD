## Agenda Personal

Este es un proyecto de Agenda Personal que permite a los usuarios gestionar sus contactos. Los usuarios pueden crear, editar, eliminar y buscar contactos. Además, pueden exportar e importar contactos en formato CSV.

## Requisitos

- PHP versión 7.4 o superior
- Composer para la gestión de dependencias
- Servidor web (por ejemplo, Apache o Nginx)
- Base de datos MySQL

## Instalación

1. Clona el repositorio en tu máquina local:

    ```sh
    git clone https://github.com/tu-usuario/agenda-personal.git
    ```

2. Navega al directorio del proyecto:

    ```sh
    cd agenda-personal
    ```

3. Instala las dependencias de Composer:

    ```sh
    composer install
    ```

4. Configura la base de datos en `src/config/database.php` con tus credenciales de MySQL.

5. Ejecuta las migraciones de la base de datos para crear las tablas necesarias:

    ```sh
    php src/migrations/migrate.php
    ```

6. Inicia el servidor web y accede a la aplicación en tu navegador:

    ```sh
    php -S localhost:8000 -t public
    ```

## Uso

### Autenticación

- **Registro**: Los usuarios pueden registrarse en la aplicación a través de `auth/register.php`.
- **Inicio de Sesión**: Los usuarios pueden iniciar sesión a través de `auth/login.php`.
- **Cerrar Sesión**: Los usuarios pueden cerrar sesión a través de `auth/logout.php`.

### Gestión de Contactos

- **Crear Contacto**: Los usuarios pueden crear nuevos contactos a través de `public/crear_contacto.php`.
- **Ver Detalles del Contacto**: Los usuarios pueden ver los detalles de un contacto a través de `public/detalle_contacto.php`.
- **Editar Contacto**: Los usuarios pueden editar un contacto a través de `public/editar_contacto.php`.
- **Eliminar Contacto**: Los usuarios pueden eliminar un contacto a través de `public/eliminar_contacto.php`.

### Importar y Exportar Contactos

- **Exportar Contactos**: Los usuarios pueden exportar sus contactos en formato CSV a través de `public/exportar_contactos.php`.
- **Importar Contactos**: Los usuarios pueden importar contactos desde un archivo CSV a través de `public/importar_contactos.php`.

## Estructura de Archivos

- **auth/**: Contiene archivos relacionados con la autenticación de usuarios.
- **public/**: Contiene los archivos accesibles públicamente.
  - **assets/**: Contiene archivos estáticos como CSS, imágenes y JavaScript.
  - **crear_contacto.php**: Lógica para crear un nuevo contacto.
  - **detalle_contacto.php**: Muestra los detalles de un contacto.
  - **editar_contacto.php**: Lógica para editar un contacto existente.
  - **eliminar_contacto.php**: Lógica para eliminar un contacto.
  - **exportar_contactos.php**: Exporta los contactos a un archivo CSV.
  - **importar_contactos.php**: Importa contactos desde un archivo CSV.
  - **index.php**: Página principal de la aplicación.
- **src/**: Contiene la lógica del servidor y la configuración.
  - **config/**: Archivos de configuración.
    - **database.php**: Configuración de la base de datos.
  - **controllers/**: Controladores de la aplicación.
  - **models/**: Modelos de la aplicación.
  - **routes.php**: Definición de rutas y lógica de la aplicación.
- **templates/**: Plantillas HTML de la aplicación.
- **vendor/**: Contiene las dependencias instaladas por Composer.

## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Agrega nueva funcionalidad'`).
4. Sube tus cambios a tu fork (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request en GitHub.




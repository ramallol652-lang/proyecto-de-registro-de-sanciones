# Sistema de Registro de Sanciones

Aplicación PHP + MySQL para registrar estudiantes, sanciones, compromisos, citaciones, seguimientos y reportes.

## Instalación en XAMPP

1. Copie la carpeta `Rsanciones` dentro de `C:\xampp\htdocs\`.
2. Inicie Apache y MySQL desde XAMPP.
3. Abra phpMyAdmin e importe `db/sanciones.sql`.
4. Visite `http://localhost/Rsanciones/public/`.
5. Ingrese con `admin` / `admin123`. En el primer acceso la contraseña se convierte automáticamente a un hash seguro.

Si MySQL usa otra contraseña o puerto, edite `config/database.php` o defina `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`.

## Arquitectura

- `controllers`: reciben solicitudes HTTP.
- `services`: reglas y validación del caso de uso.
- `repositories`: persistencia SQL mediante PDO.
- `contracts`: interfaces para invertir dependencias.
- `views`: interfaz y formularios.
- `core`: conexión, sesión, CSRF y utilidades comunes.

El sistema utiliza consultas preparadas, escape de salida HTML, protección CSRF, regeneración de sesión y migración de la contraseña inicial a `password_hash`.

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Sanciones</title>
    <link rel="stylesheet" href="css/estilos.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="login-body">

<div class="login-box">
    <h2>Sistema de Sanciones</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">
            Usuario o contraseña incorrectos
        </div>
    <?php endif; ?>

    <form action="index.php?url=validar-login" method="POST">
        <?php echo Csrf::input(); ?>
        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>
    <p class="hint">Acceso inicial: admin / admin123</p>
</div>

</body>
</html>

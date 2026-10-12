<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo estudiante</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="header">
    <h2>Sistema de Registro de Sanciones</h2>
</header>

<main class="container">

    <div class="card">
        <h3>Registrar nuevo estudiante</h3>

        <form action="index.php?url=estudiantes-guardar" method="POST">
            <?php echo Csrf::input(); ?>

            <label>Código del estudiante</label>
            <input type="text" name="codigo_estudiante" placeholder="Ej: EST-2026-0002" required>

            <label>CI</label>
            <input type="text" name="ci" placeholder="Carnet de identidad">

            <label>Nombres</label>
            <input type="text" name="nombres" required>

            <label>Apellidos</label>
            <input type="text" name="apellidos" required>

            <label>Sexo</label>
            <select name="sexo" required>
                <option value="">Seleccione...</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>

            <label>Curso</label>
            <select name="id_curso" required>
                <option value="">Seleccione un curso...</option>

                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['id_curso']; ?>">
                        <?php 
                            echo htmlspecialchars(
                                $curso['gestion'] . " - " .
                                $curso['nivel'] . " " .
                                $curso['curso'] . " " .
                                $curso['paralelo'] . " - " .
                                $curso['turno']
                            ); 
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Nombre del tutor</label>
            <input type="text" name="nombre_tutor" required>

            <label>Teléfono del tutor</label>
            <input type="text" name="telefono_tutor" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Activo">Activo</option>
                <option value="Retirado">Retirado</option>
            </select>

            <br><br>

            <button type="submit">Guardar estudiante</button>

            <br><br>

            <a class="btn-secondary" href="index.php?url=estudiantes">Cancelar</a>

        </form>
    </div>

</main>

</body>
</html>

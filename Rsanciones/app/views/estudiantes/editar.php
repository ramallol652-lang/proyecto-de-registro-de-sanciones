<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar estudiante</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="header">
    <h2>Sistema de Registro de Sanciones</h2>
</header>

<main class="container">

    <div class="card">
        <h3>Editar estudiante</h3>

        <form action="index.php?url=estudiantes-actualizar" method="POST">
            <?php echo Csrf::input(); ?>

            <input type="hidden" name="id_estudiante" value="<?php echo $estudiante['id_estudiante']; ?>">

            <label>Código del estudiante</label>
            <input type="text" name="codigo_estudiante" value="<?php echo htmlspecialchars($estudiante['codigo_estudiante']); ?>" required>

            <label>CI</label>
            <input type="text" name="ci" value="<?php echo htmlspecialchars($estudiante['ci'] ?? ''); ?>">

            <label>Nombres</label>
            <input type="text" name="nombres" value="<?php echo htmlspecialchars($estudiante['nombres']); ?>" required>

            <label>Apellidos</label>
            <input type="text" name="apellidos" value="<?php echo htmlspecialchars($estudiante['apellidos']); ?>" required>

            <label>Sexo</label>
            <select name="sexo" required>
                <option value="">Seleccione...</option>
                <option value="M" <?php echo $estudiante['sexo'] == 'M' ? 'selected' : ''; ?>>Masculino</option>
                <option value="F" <?php echo $estudiante['sexo'] == 'F' ? 'selected' : ''; ?>>Femenino</option>
            </select>

            <label>Curso</label>
            <select name="id_curso" required>
                <option value="">Seleccione un curso...</option>

                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['id_curso']; ?>"
                        <?php echo $curso['id_curso'] == $estudiante['id_curso'] ? 'selected' : ''; ?>>
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
            <input type="text" name="nombre_tutor" value="<?php echo htmlspecialchars($estudiante['nombre_tutor']); ?>" required>

            <label>Teléfono del tutor</label>
            <input type="text" name="telefono_tutor" value="<?php echo htmlspecialchars($estudiante['telefono_tutor']); ?>" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Activo" <?php echo $estudiante['estado'] == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="Retirado" <?php echo $estudiante['estado'] == 'Retirado' ? 'selected' : ''; ?>>Retirado</option>
            </select>

            <br><br>

            <button type="submit">Actualizar estudiante</button>

            <br><br>

            <a class="btn-secondary" href="index.php?url=estudiantes">Cancelar</a>

        </form>
    </div>

</main>

</body>
</html>

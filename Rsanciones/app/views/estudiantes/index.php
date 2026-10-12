<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estudiantes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="header">
    <h2>Sistema de Registro de Sanciones</h2>
</header>

<main class="container">

    <div class="card">
        <h3>Listado de estudiantes</h3>

        <div class="top-actions">
            <a class="btn" href="index.php?url=estudiantes-crear">Nuevo estudiante</a>
            <a class="btn-secondary" href="index.php?url=panel">Volver al panel</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>CI</th>
                    <th>Estudiante</th>
                    <th>Sexo</th>
                    <th>Curso</th>
                    <th>Tutor</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($estudiantes)): ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($estudiante['codigo_estudiante']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['ci'] ?? ''); ?></td>
                            <td>
                                <?php echo htmlspecialchars($estudiante['apellidos'] . ' ' . $estudiante['nombres']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($estudiante['sexo'] ?? ''); ?></td>
                            <td>
                                <?php 
                                    echo htmlspecialchars(
                                        $estudiante['nivel'] . ' ' .
                                        $estudiante['curso'] . ' ' .
                                        $estudiante['paralelo'] . ' - ' .
                                        $estudiante['turno']
                                    ); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($estudiante['nombre_tutor']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['telefono_tutor']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['estado']); ?></td>
                            <td class="acciones">
                                <a class="btn-small" href="index.php?url=estudiantes-editar&id=<?php echo $estudiante['id_estudiante']; ?>">
                                    Editar
                                </a>

                                <form class="inline" action="index.php?url=estudiantes-eliminar" method="POST" onsubmit="return confirm('¿Seguro que deseas retirar este estudiante?');">
                                    <?php echo Csrf::input(); ?><input type="hidden" name="id" value="<?php echo $estudiante['id_estudiante']; ?>"><button class="btn-danger" type="submit">Retirar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No hay estudiantes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

</body>
</html>

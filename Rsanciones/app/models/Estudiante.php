<?php

require_once __DIR__ . "/../../core/Database.php";

class Estudiante
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listar()
    {
        $sql = "SELECT 
                    e.id_estudiante,
                    e.codigo_estudiante,
                    e.ci,
                    e.nombres,
                    e.apellidos,
                    e.sexo,
                    e.nombre_tutor,
                    e.telefono_tutor,
                    e.estado,
                    c.nivel,
                    c.curso,
                    c.paralelo,
                    c.turno,
                    g.gestion
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                INNER JOIN gestiones g ON c.id_gestion = g.id_gestion
                WHERE e.estado = 'Activo'
                ORDER BY e.apellidos, e.nombres";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarCursos()
    {
        $sql = "SELECT 
                    c.id_curso,
                    c.nivel,
                    c.curso,
                    c.paralelo,
                    c.turno,
                    g.gestion
                FROM cursos c
                INNER JOIN gestiones g ON c.id_gestion = g.id_gestion
                ORDER BY g.gestion DESC, c.nivel, c.curso, c.paralelo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO estudiantes 
                (
                    codigo_estudiante,
                    ci,
                    nombres,
                    apellidos,
                    sexo,
                    id_curso,
                    nombre_tutor,
                    telefono_tutor,
                    estado
                )
                VALUES
                (
                    :codigo_estudiante,
                    :ci,
                    :nombres,
                    :apellidos,
                    :sexo,
                    :id_curso,
                    :nombre_tutor,
                    :telefono_tutor,
                    :estado
                )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(":codigo_estudiante", $datos['codigo_estudiante']);
        $stmt->bindParam(":ci", $datos['ci']);
        $stmt->bindParam(":nombres", $datos['nombres']);
        $stmt->bindParam(":apellidos", $datos['apellidos']);
        $stmt->bindParam(":sexo", $datos['sexo']);
        $stmt->bindParam(":id_curso", $datos['id_curso']);
        $stmt->bindParam(":nombre_tutor", $datos['nombre_tutor']);
        $stmt->bindParam(":telefono_tutor", $datos['telefono_tutor']);
        $stmt->bindParam(":estado", $datos['estado']);

        return $stmt->execute();
    }

    public function obtenerPorId($id_estudiante)
    {
        $sql = "SELECT * FROM estudiantes 
                WHERE id_estudiante = :id_estudiante 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_estudiante", $id_estudiante);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function actualizar($datos)
    {
        $sql = "UPDATE estudiantes SET
                    codigo_estudiante = :codigo_estudiante,
                    ci = :ci,
                    nombres = :nombres,
                    apellidos = :apellidos,
                    sexo = :sexo,
                    id_curso = :id_curso,
                    nombre_tutor = :nombre_tutor,
                    telefono_tutor = :telefono_tutor,
                    estado = :estado
                WHERE id_estudiante = :id_estudiante";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(":codigo_estudiante", $datos['codigo_estudiante']);
        $stmt->bindParam(":ci", $datos['ci']);
        $stmt->bindParam(":nombres", $datos['nombres']);
        $stmt->bindParam(":apellidos", $datos['apellidos']);
        $stmt->bindParam(":sexo", $datos['sexo']);
        $stmt->bindParam(":id_curso", $datos['id_curso']);
        $stmt->bindParam(":nombre_tutor", $datos['nombre_tutor']);
        $stmt->bindParam(":telefono_tutor", $datos['telefono_tutor']);
        $stmt->bindParam(":estado", $datos['estado']);
        $stmt->bindParam(":id_estudiante", $datos['id_estudiante']);

        return $stmt->execute();
    }

    public function eliminar($id_estudiante)
    {
        $sql = "UPDATE estudiantes 
                SET estado = 'Retirado'
                WHERE id_estudiante = :id_estudiante";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_estudiante", $id_estudiante);

        return $stmt->execute();
    }
}
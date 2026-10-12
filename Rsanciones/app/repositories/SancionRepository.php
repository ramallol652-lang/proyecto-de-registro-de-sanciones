<?php

class SancionRepository implements SancionRepositoryInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listar($filtros = [])
    {
        $sql = "SELECT s.*, CONCAT(e.apellidos, ' ', e.nombres) estudiante,
                       e.codigo_estudiante, t.nombre tipo, t.puntos,
                       u.nombre_completo responsable, g.gestion
                FROM sanciones s
                JOIN estudiantes e ON e.id_estudiante=s.id_estudiante
                JOIN tipos_sancion t ON t.id_tipo=s.id_tipo
                JOIN usuarios u ON u.id_usuario=s.id_usuario
                JOIN gestiones g ON g.id_gestion=s.id_gestion WHERE 1=1";
        $params = [];
        if (!empty($filtros['buscar'])) {
            $sql .= " AND (s.numero_caso LIKE :buscar OR e.nombres LIKE :buscar OR e.apellidos LIKE :buscar)";
            $params['buscar'] = '%' . $filtros['buscar'] . '%';
        }
        if (!empty($filtros['estado'])) {
            $sql .= " AND s.estado=:estado";
            $params['estado'] = $filtros['estado'];
        }
        $sql .= " ORDER BY s.fecha_sancion DESC, s.id_sancion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtener($id)
    {
        $stmt = $this->db->prepare("SELECT s.*, CONCAT(e.apellidos, ' ', e.nombres) estudiante,
                    e.codigo_estudiante, e.nombre_tutor, e.telefono_tutor,
                    t.nombre tipo, t.puntos, t.requiere_citacion,
                    u.nombre_completo responsable, g.gestion
                FROM sanciones s
                JOIN estudiantes e ON e.id_estudiante=s.id_estudiante
                JOIN tipos_sancion t ON t.id_tipo=s.id_tipo
                JOIN usuarios u ON u.id_usuario=s.id_usuario
                JOIN gestiones g ON g.id_gestion=s.id_gestion
                WHERE s.id_sancion=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function crear($datos)
    {
        $stmt = $this->db->prepare("INSERT INTO sanciones
            (numero_caso,id_estudiante,id_tipo,id_usuario,id_gestion,fecha_sancion,descripcion,accion_tomada,estado)
            VALUES (:numero_caso,:id_estudiante,:id_tipo,:id_usuario,:id_gestion,:fecha_sancion,:descripcion,:accion_tomada,'Abierta')");
        $stmt->execute($datos);
        return (int) $this->db->lastInsertId();
    }

    public function actualizar($id, $datos)
    {
        $datos['id'] = $id;
        $stmt = $this->db->prepare("UPDATE sanciones SET id_estudiante=:id_estudiante,id_tipo=:id_tipo,
            id_gestion=:id_gestion,fecha_sancion=:fecha_sancion,descripcion=:descripcion,
            accion_tomada=:accion_tomada WHERE id_sancion=:id");
        return $stmt->execute($datos);
    }

    public function cambiarEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE sanciones SET estado=:estado WHERE id_sancion=:id");
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }

    public function catalogos()
    {
        return [
            'estudiantes' => $this->db->query("SELECT id_estudiante,codigo_estudiante,nombres,apellidos FROM estudiantes WHERE estado='Activo' ORDER BY apellidos,nombres")->fetchAll(),
            'tipos' => $this->db->query("SELECT * FROM tipos_sancion ORDER BY puntos")->fetchAll(),
            'gestiones' => $this->db->query("SELECT * FROM gestiones ORDER BY gestion DESC")->fetchAll()
        ];
    }
}

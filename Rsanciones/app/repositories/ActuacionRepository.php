<?php

class ActuacionRepository
{
    private $db;

    public function __construct(PDO $db) { $this->db = $db; }

    public function detallePorSancion($id)
    {
        $queries = [
            'compromiso' => "SELECT * FROM compromisos WHERE id_sancion=:id LIMIT 1",
            'citaciones' => "SELECT * FROM citaciones WHERE id_sancion=:id ORDER BY fecha_citacion DESC,hora_citacion DESC",
            'seguimientos' => "SELECT * FROM seguimientos WHERE id_sancion=:id ORDER BY fecha DESC,id_seguimiento DESC"
        ];
        $result = [];
        foreach ($queries as $key => $sql) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result[$key] = $key === 'compromiso' ? $stmt->fetch() : $stmt->fetchAll();
        }
        return $result;
    }

    public function guardarCompromiso($datos)
    {
        $sql = "INSERT INTO compromisos (id_sancion,fecha_compromiso,texto_compromiso,firma_estudiante,firma_tutor,firma_responsable,estado,observaciones)
                VALUES (:id_sancion,:fecha_compromiso,:texto_compromiso,:firma_estudiante,:firma_tutor,:firma_responsable,:estado,:observaciones)
                ON DUPLICATE KEY UPDATE fecha_compromiso=VALUES(fecha_compromiso),texto_compromiso=VALUES(texto_compromiso),
                firma_estudiante=VALUES(firma_estudiante),firma_tutor=VALUES(firma_tutor),firma_responsable=VALUES(firma_responsable),
                estado=VALUES(estado),observaciones=VALUES(observaciones)";
        return $this->db->prepare($sql)->execute($datos);
    }

    public function crearCitacion($datos)
    {
        $sql = "INSERT INTO citaciones (id_sancion,fecha_citacion,hora_citacion,nombre_apoderado,asistio,fecha_asistencia,observaciones)
                VALUES (:id_sancion,:fecha_citacion,:hora_citacion,:nombre_apoderado,:asistio,:fecha_asistencia,:observaciones)";
        return $this->db->prepare($sql)->execute($datos);
    }

    public function crearSeguimiento($datos)
    {
        $sql = "INSERT INTO seguimientos (id_sancion,fecha,responsable,observacion,resultado)
                VALUES (:id_sancion,:fecha,:responsable,:observacion,:resultado)";
        return $this->db->prepare($sql)->execute($datos);
    }
}

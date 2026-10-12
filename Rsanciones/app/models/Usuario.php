<?php

require_once __DIR__ . "/../../core/Database.php";

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function buscarPorUsuario($usuario)
    {
        $sql = "SELECT * FROM usuarios 
                WHERE usuario = :usuario 
                AND estado = 'Activo'
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function actualizarPassword($id, $hash)
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET password=:password WHERE id_usuario=:id');
        return $stmt->execute(['password'=>$hash,'id'=>$id]);
    }
}

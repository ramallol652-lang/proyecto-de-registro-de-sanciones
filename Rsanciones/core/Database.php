<?php

class Database
{
    private static $conexion = null;

    public static function conectar()
    {
        if (self::$conexion === null) {

            $config = require __DIR__ . "/../config/database.php";

            try {
                self::$conexion = new PDO(
                    "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}",
                    $config['user'],
                    $config['password']
                );

                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log($e->getMessage());
                http_response_code(500);
                die("No se pudo conectar con la base de datos. Revise config/database.php.");
            }
        }

        return self::$conexion;
    }
}

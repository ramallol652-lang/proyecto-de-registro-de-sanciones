<?php

class Auth
{
    public static function start()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function check()
    {
        self::start();
        return isset($_SESSION['id_usuario']);
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            header('Location: index.php?url=login');
            exit;
        }
    }

    public static function userId()
    {
        self::start();
        return isset($_SESSION['id_usuario']) ? (int) $_SESSION['id_usuario'] : null;
    }
}

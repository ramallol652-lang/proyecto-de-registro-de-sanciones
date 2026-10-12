<?php

class Csrf
{
    public static function token()
    {
        Auth::start();
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }

    public static function input()
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify()
    {
        Auth::start();
        $token = isset($_POST['_token']) ? $_POST['_token'] : '';
        if (!$token || !hash_equals(isset($_SESSION['_token']) ? $_SESSION['_token'] : '', $token)) {
            http_response_code(419);
            die('La sesión del formulario expiró. Regrese e inténtelo nuevamente.');
        }
    }
}

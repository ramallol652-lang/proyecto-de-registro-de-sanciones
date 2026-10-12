<?php

class Flash
{
    public static function set($type, $message)
    {
        Auth::start();
        $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
    }

    public static function get()
    {
        Auth::start();
        $flash = isset($_SESSION['_flash']) ? $_SESSION['_flash'] : null;
        unset($_SESSION['_flash']);
        return $flash;
    }
}

<?php

class Controller
{
    public function view($ruta, $datos = [])
    {
        extract($datos);

        require __DIR__ . "/../app/views/" . $ruta . ".php";
    }

    protected function redirect($ruta)
    {
        header("Location: index.php?url=" . $ruta);
        exit;
    }

    protected function requireAuth()
    {
        Auth::requireLogin();
    }
}

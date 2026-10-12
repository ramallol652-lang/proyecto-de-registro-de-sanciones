<?php

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            header("Location: index.php?url=panel");
            exit;
        }

        $this->view("auth/login");
    }

    public function validar()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: index.php?url=login");
            exit;
        }

        Csrf::verify();
        $usuario = trim(isset($_POST['usuario']) ? $_POST['usuario'] : '');
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $usuarioModelo = new Usuario();
        $datosUsuario = $usuarioModelo->buscarPorUsuario($usuario);

        $valida = $datosUsuario && (password_verify($password, $datosUsuario['password']) || hash_equals($datosUsuario['password'], $password));
        if ($valida) {
            session_regenerate_id(true);
            if (password_get_info($datosUsuario['password'])['algo'] === 0) {
                $usuarioModelo->actualizarPassword($datosUsuario['id_usuario'], password_hash($password, PASSWORD_DEFAULT));
            }

            $_SESSION['id_usuario'] = $datosUsuario['id_usuario'];
            $_SESSION['nombre_completo'] = $datosUsuario['nombre_completo'];
            $_SESSION['usuario'] = $datosUsuario['usuario'];
            $_SESSION['rol'] = $datosUsuario['rol'];

            header("Location: index.php?url=panel");
            exit;

        } else {
            header("Location: index.php?url=login&error=1");
            exit;
        }
    }

    public function logout()
    {
        Auth::start();
        session_destroy();

        header("Location: index.php?url=login");
        exit;
    }
}

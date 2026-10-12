<?php

require_once __DIR__ . "/../../core/Controller.php";
require_once __DIR__ . "/../models/Estudiante.php";

class EstudianteController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $estudianteModelo = new Estudiante();
        $estudiantes = $estudianteModelo->listar();

        $this->view("estudiantes/index", [
            "estudiantes" => $estudiantes
        ]);
    }

    public function crear()
    {
        $this->requireAuth();

        $estudianteModelo = new Estudiante();
        $cursos = $estudianteModelo->listarCursos();

        $this->view("estudiantes/crear", [
            "cursos" => $cursos
        ]);
    }

    public function guardar()
    {
        $this->requireAuth();
        Csrf::verify();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: index.php?url=estudiantes");
            exit;
        }

        $datos = [
            "codigo_estudiante" => trim($_POST['codigo_estudiante']),
            "ci" => trim($_POST['ci']),
            "nombres" => trim($_POST['nombres']),
            "apellidos" => trim($_POST['apellidos']),
            "sexo" => $_POST['sexo'],
            "id_curso" => $_POST['id_curso'],
            "nombre_tutor" => trim($_POST['nombre_tutor']),
            "telefono_tutor" => trim($_POST['telefono_tutor']),
            "estado" => $_POST['estado']
        ];

        $estudianteModelo = new Estudiante();
        $estudianteModelo->guardar($datos);

        header("Location: index.php?url=estudiantes");
        exit;
    }

    public function editar()
    {
        $this->requireAuth();

        $id_estudiante = $_GET['id'] ?? null;

        if (!$id_estudiante) {
            header("Location: index.php?url=estudiantes");
            exit;
        }

        $estudianteModelo = new Estudiante();

        $estudiante = $estudianteModelo->obtenerPorId($id_estudiante);
        $cursos = $estudianteModelo->listarCursos();

        if (!$estudiante) {
            header("Location: index.php?url=estudiantes");
            exit;
        }

        $this->view("estudiantes/editar", [
            "estudiante" => $estudiante,
            "cursos" => $cursos
        ]);
    }

    public function actualizar()
    {
        $this->requireAuth();
        Csrf::verify();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: index.php?url=estudiantes");
            exit;
        }

        $datos = [
            "id_estudiante" => $_POST['id_estudiante'],
            "codigo_estudiante" => trim($_POST['codigo_estudiante']),
            "ci" => trim($_POST['ci']),
            "nombres" => trim($_POST['nombres']),
            "apellidos" => trim($_POST['apellidos']),
            "sexo" => $_POST['sexo'],
            "id_curso" => $_POST['id_curso'],
            "nombre_tutor" => trim($_POST['nombre_tutor']),
            "telefono_tutor" => trim($_POST['telefono_tutor']),
            "estado" => $_POST['estado']
        ];

        $estudianteModelo = new Estudiante();
        $estudianteModelo->actualizar($datos);

        header("Location: index.php?url=estudiantes");
        exit;
    }

    public function eliminar()
    {
        $this->requireAuth();
        Csrf::verify();
        $id_estudiante = $_POST['id'] ?? null;

        if ($id_estudiante) {
            $estudianteModelo = new Estudiante();
            $estudianteModelo->eliminar($id_estudiante);
        }

        header("Location: index.php?url=estudiantes");
        exit;
    }
}

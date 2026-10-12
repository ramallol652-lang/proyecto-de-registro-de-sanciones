<?php

class SancionController extends Controller
{
    private function repository() { return new SancionRepository(Database::conectar()); }

    public function index()
    {
        $this->requireAuth();
        $filtros = ['buscar' => trim(isset($_GET['buscar']) ? $_GET['buscar'] : ''), 'estado' => isset($_GET['estado']) ? $_GET['estado'] : ''];
        $this->view('sanciones/index', ['sanciones' => $this->repository()->listar($filtros), 'filtros' => $filtros, 'flash' => Flash::get()]);
    }

    public function crear()
    {
        $this->requireAuth();
        $this->view('sanciones/form', ['sancion' => null, 'catalogos' => $this->repository()->catalogos(), 'error' => null]);
    }

    public function guardar()
    {
        $this->requireAuth(); Csrf::verify();
        try {
            $id = (new SancionService($this->repository()))->registrar($_POST, Auth::userId());
            Flash::set('success', 'Sanción registrada correctamente.');
            $this->redirect('sanciones-ver&id=' . $id);
        } catch (Exception $e) {
            $this->view('sanciones/form', ['sancion' => $_POST, 'catalogos' => $this->repository()->catalogos(), 'error' => $e->getMessage()]);
        }
    }

    public function ver()
    {
        $this->requireAuth();
        $id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
        $sancion = $this->repository()->obtener($id);
        if (!$sancion) { http_response_code(404); die('Sanción no encontrada.'); }
        $actuaciones = (new ActuacionRepository(Database::conectar()))->detallePorSancion($id);
        $this->view('sanciones/ver', compact('sancion','actuaciones') + ['flash' => Flash::get()]);
    }

    public function editar()
    {
        $this->requireAuth();
        $sancion = $this->repository()->obtener((int)$_GET['id']);
        if (!$sancion) { http_response_code(404); die('Sanción no encontrada.'); }
        $this->view('sanciones/form', ['sancion' => $sancion, 'catalogos' => $this->repository()->catalogos(), 'error' => null]);
    }

    public function actualizar()
    {
        $this->requireAuth(); Csrf::verify();
        $id = (int)$_POST['id_sancion'];
        try {
            (new SancionService($this->repository()))->editar($id, $_POST);
            Flash::set('success', 'Sanción actualizada correctamente.');
            $this->redirect('sanciones-ver&id=' . $id);
        } catch (Exception $e) {
            $datos = $_POST; $datos['id_sancion'] = $id;
            $this->view('sanciones/form', ['sancion' => $datos, 'catalogos' => $this->repository()->catalogos(), 'error' => $e->getMessage()]);
        }
    }

    public function estado()
    {
        $this->requireAuth(); Csrf::verify();
        $permitidos = ['Abierta','En seguimiento','Cerrada'];
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        if (!in_array($estado, $permitidos, true)) { http_response_code(422); die('Estado inválido.'); }
        $id = (int)$_POST['id_sancion'];
        $this->repository()->cambiarEstado($id, $estado);
        Flash::set('success', 'Estado actualizado.');
        $this->redirect('sanciones-ver&id=' . $id);
    }
}

<?php

class PanelController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $db=Database::conectar();
        $metricas=$db->query("SELECT (SELECT COUNT(*) FROM estudiantes WHERE estado='Activo') estudiantes,
            (SELECT COUNT(*) FROM sanciones WHERE estado='Abierta') abiertas,
            (SELECT COUNT(*) FROM sanciones WHERE estado='En seguimiento') seguimiento,
            (SELECT COUNT(*) FROM citaciones WHERE asistio='Pendiente') citaciones")->fetch();
        $recientes=(new SancionRepository($db))->listar([]);
        $recientes=array_slice($recientes,0,5);
        $this->view("panel/index",compact('metricas','recientes'));
    }
}

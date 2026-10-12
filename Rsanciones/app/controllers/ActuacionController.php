<?php

class ActuacionController extends Controller
{
    private function repo() { return new ActuacionRepository(Database::conectar()); }
    private function back($id) { Flash::set('success', 'Información guardada correctamente.'); $this->redirect('sanciones-ver&id=' . $id); }

    public function compromiso()
    {
        $this->requireAuth(); Csrf::verify();
        $id = (int)$_POST['id_sancion'];
        if (trim($_POST['texto_compromiso']) === '') { Flash::set('error','Escriba el compromiso.'); $this->redirect('sanciones-ver&id='.$id); }
        $this->repo()->guardarCompromiso([
            'id_sancion'=>$id,'fecha_compromiso'=>$_POST['fecha_compromiso'],'texto_compromiso'=>trim($_POST['texto_compromiso']),
            'firma_estudiante'=>trim($_POST['firma_estudiante']),'firma_tutor'=>trim($_POST['firma_tutor']),
            'firma_responsable'=>trim($_POST['firma_responsable']),'estado'=>$_POST['estado'],'observaciones'=>trim($_POST['observaciones'])
        ]);
        $this->back($id);
    }

    public function citacion()
    {
        $this->requireAuth(); Csrf::verify(); $id=(int)$_POST['id_sancion'];
        $this->repo()->crearCitacion([
            'id_sancion'=>$id,'fecha_citacion'=>$_POST['fecha_citacion'],'hora_citacion'=>$_POST['hora_citacion'],
            'nombre_apoderado'=>trim($_POST['nombre_apoderado']),'asistio'=>$_POST['asistio'],
            'fecha_asistencia'=>!empty($_POST['fecha_asistencia'])?$_POST['fecha_asistencia']:null,'observaciones'=>trim($_POST['observaciones'])
        ]);
        $this->back($id);
    }

    public function seguimiento()
    {
        $this->requireAuth(); Csrf::verify(); $id=(int)$_POST['id_sancion'];
        $this->repo()->crearSeguimiento([
            'id_sancion'=>$id,'fecha'=>$_POST['fecha'],'responsable'=>trim($_POST['responsable']),
            'observacion'=>trim($_POST['observacion']),'resultado'=>$_POST['resultado']
        ]);
        $this->back($id);
    }
}

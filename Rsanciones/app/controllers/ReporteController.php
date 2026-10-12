<?php

class ReporteController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $db=Database::conectar();
        $resumen=$db->query("SELECT COUNT(*) total,
            SUM(estado='Abierta') abiertas,SUM(estado='En seguimiento') seguimiento,SUM(estado='Cerrada') cerradas
            FROM sanciones")->fetch();
        $porTipo=$db->query("SELECT t.nombre,COUNT(*) cantidad,SUM(t.puntos) puntos FROM sanciones s JOIN tipos_sancion t ON t.id_tipo=s.id_tipo GROUP BY t.id_tipo,t.nombre ORDER BY t.puntos")->fetchAll();
        $reincidentes=$db->query("SELECT CONCAT(e.apellidos,' ',e.nombres) estudiante,e.codigo_estudiante,COUNT(*) cantidad,SUM(t.puntos) puntos
            FROM sanciones s JOIN estudiantes e ON e.id_estudiante=s.id_estudiante JOIN tipos_sancion t ON t.id_tipo=s.id_tipo
            GROUP BY e.id_estudiante,e.apellidos,e.nombres,e.codigo_estudiante HAVING COUNT(*)>1 ORDER BY cantidad DESC,puntos DESC")->fetchAll();
        $this->view('reportes/index',compact('resumen','porTipo','reincidentes'));
    }

    public function csv()
    {
        $this->requireAuth();
        $rows=(new SancionRepository(Database::conectar()))->listar([]);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte_sanciones_'.date('Y-m-d').'.csv"');
        echo "\xEF\xBB\xBF";
        $out=fopen('php://output','w');
        fputcsv($out,['Caso','Fecha','Estudiante','Código','Tipo','Puntos','Responsable','Estado'],';');
        foreach($rows as $r){ fputcsv($out,[$r['numero_caso'],$r['fecha_sancion'],$r['estudiante'],$r['codigo_estudiante'],$r['tipo'],$r['puntos'],$r['responsable'],$r['estado']],';'); }
        fclose($out); exit;
    }
}

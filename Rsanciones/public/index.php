<?php
require_once __DIR__ . '/../core/bootstrap.php';
$routes = [
 'login'=>[AuthController::class,'login'],'validar-login'=>[AuthController::class,'validar'],'logout'=>[AuthController::class,'logout'],
 'panel'=>[PanelController::class,'index'],'estudiantes'=>[EstudianteController::class,'index'],'estudiantes-crear'=>[EstudianteController::class,'crear'],
 'estudiantes-guardar'=>[EstudianteController::class,'guardar'],'estudiantes-editar'=>[EstudianteController::class,'editar'],
 'estudiantes-actualizar'=>[EstudianteController::class,'actualizar'],'estudiantes-eliminar'=>[EstudianteController::class,'eliminar'],
 'sanciones'=>[SancionController::class,'index'],'sanciones-crear'=>[SancionController::class,'crear'],'sanciones-guardar'=>[SancionController::class,'guardar'],
 'sanciones-ver'=>[SancionController::class,'ver'],'sanciones-editar'=>[SancionController::class,'editar'],'sanciones-actualizar'=>[SancionController::class,'actualizar'],
 'sanciones-estado'=>[SancionController::class,'estado'],'compromisos-guardar'=>[ActuacionController::class,'compromiso'],
 'citaciones-guardar'=>[ActuacionController::class,'citacion'],'seguimientos-guardar'=>[ActuacionController::class,'seguimiento'],
 'reportes'=>[ReporteController::class,'index'],'reportes-csv'=>[ReporteController::class,'csv']
];
$url = isset($_GET['url']) ? $_GET['url'] : (Auth::check() ? 'panel' : 'login');
if (!isset($routes[$url])) { http_response_code(404); die('Página no encontrada.'); }
list($controller,$method)=$routes[$url]; (new $controller())->$method();

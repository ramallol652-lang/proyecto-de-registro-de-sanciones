<?php

interface SancionRepositoryInterface
{
    public function listar($filtros = []);
    public function obtener($id);
    public function crear($datos);
    public function actualizar($id, $datos);
    public function cambiarEstado($id, $estado);
    public function catalogos();
}

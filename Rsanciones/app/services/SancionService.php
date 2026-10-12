<?php

class SancionService
{
    private $repository;

    public function __construct(SancionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function registrar($entrada, $usuarioId)
    {
        $datos = $this->validar($entrada);
        $datos['id_usuario'] = $usuarioId;
        $datos['numero_caso'] = $this->generarNumeroCaso();
        return $this->repository->crear($datos);
    }

    public function editar($id, $entrada)
    {
        $datos = $this->validar($entrada);
        unset($datos['id_usuario'], $datos['numero_caso']);
        return $this->repository->actualizar($id, $datos);
    }

    private function validar($entrada)
    {
        $required = ['id_estudiante','id_tipo','id_gestion','fecha_sancion','descripcion'];
        foreach ($required as $campo) {
            if (!isset($entrada[$campo]) || trim((string)$entrada[$campo]) === '') {
                throw new InvalidArgumentException('Complete todos los campos obligatorios.');
            }
        }
        $fecha = DateTime::createFromFormat('Y-m-d', $entrada['fecha_sancion']);
        if (!$fecha || $fecha->format('Y-m-d') !== $entrada['fecha_sancion']) {
            throw new InvalidArgumentException('La fecha de la sanción no es válida.');
        }
        return [
            'id_estudiante' => (int)$entrada['id_estudiante'],
            'id_tipo' => (int)$entrada['id_tipo'],
            'id_gestion' => (int)$entrada['id_gestion'],
            'fecha_sancion' => $entrada['fecha_sancion'],
            'descripcion' => trim($entrada['descripcion']),
            'accion_tomada' => trim(isset($entrada['accion_tomada']) ? $entrada['accion_tomada'] : '')
        ];
    }

    private function generarNumeroCaso()
    {
        return 'DS-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }
}

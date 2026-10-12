<?php
class Estudiante {
    private string $nombre;
    private array $calificaciones = [];

    public function __construct(string $nombre) {
        $this->nombre = $nombre;
    }

    public function agregarCalificacion(float $valor): void {
        $this->calificaciones[] = $valor;
    }

    public function promedio(): float {
        if (count($this->calificaciones) === 0) {
            return 0.0;
        }
        return array_sum($this->calificaciones) / count($this->calificaciones);
    }
}

$alumno = new Estudiante("Prueba Docente");
$alumno->agregarCalificacion(85);
$alumno->agregarCalificacion(92);

echo "Promedio: " . number_format($alumno->promedio(), 2);
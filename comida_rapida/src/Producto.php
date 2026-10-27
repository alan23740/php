<?php
declare(strict_types=1);
require_once __DIR__ . "/Categoria.php";
class Producto{
    private const CAMPOS_OBLIGATORIOS = ["id", "nombre", "categoria", "precio", "descripcion","stock","imagen"];
    private int $id;
    private string $nombre;
    private Categoria $categoria;
    private float $precio;
    private string $descripcion;
    private int $stock;
    private string $imagen;
    private function __construct(int $id, string $nombre, Categoria $categoria, float $precio, string $descripcion, int $stock, string $imagen){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->stock = $stock;
        $this->imagen = $imagen;
    }
    public function getId(): int{
        return $this->id;
    }
    public function getNombre(): string{
        return $this->nombre;
    }
    public function getCategoria(): Categoria{
        return $this->categoria;
    }
    public function estaDisponible(): bool{
        return $this->stock > 0;
    }
    public function getPrecioFormateado(): string{
        return "$" . number_format($this->precio, 2);
    }

    public function getDescripcion(): string{
        return $this->descripcion;
    }
    public function getStock(): int{
        return $this->stock;
    }
    public function getImagen(): string{
        return $this->imagen;
    }
}
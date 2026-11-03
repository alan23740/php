<?php
declare(strict_types=1);
require_once __DIR__ . "/Categoria.php";

class Producto
{
    private const CAMPOS_OBLIGATORIOS = ['id', 'nombre', 'categoria', 'precio', 'descripcion', 'stock', 'imagen'];

    private int $id;
    private string $nombre;
    private Categoria $categoria;
    private float $precio;
    private string $descripcion;
    private int $stock;
    private string $imagen;

    private function __construct(int $id, string $nombre, Categoria $categoria, float $precio, string $descripcion, int $stock, string $imagen)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->stock = $stock;
        $this->imagen = $imagen;
    }
    private static function exigirCamposObligatorios(array $datos): void
    {
        foreach (self::CAMPOS_OBLIGATORIOS as $campo) {
            if (!array_key_exists($campo, $datos)) {
                throw new InvalidArgumentException("Falta el campo obligatorio: {$campo}");
            }
        }
    }

   public static function desdeArray(array $datos): self
    {
        self::exigirCamposObligatorios($datos);
        $categoria = new Categoria($datos["categoria"]);
        return new self(
            (int)$datos["id"],
            (string)$datos["nombre"],
            $categoria,
            (float)$datos["precio"],
            (string)$datos["descripcion"],
            (int)$datos["stock"],
            (string)$datos["imagen"]
        );
    }
    

    public function getId(): int
    {
        return $this->id;
    }

    public function obtenerId(): int
    {
        return $this->getId();
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function obtenerNombre(): string
    {
        return $this->getNombre();
    }

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function obtenerCategoria(): Categoria
    {
        return $this->getCategoria();
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function obtenerPrecio(): float
    {
        return $this->getPrecio();
    }

    public function getPrecioFormateado(): string
    {
        return '$' . number_format($this->precio, 2);
    }

    public function obtenerPrecioFormateado(): string
    {
        return $this->getPrecioFormateado();
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function obtenerDescripcion(): string
    {
        return $this->getDescripcion();
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function obtenerStock(): int
    {
        return $this->getStock();
    }

    public function estaDisponible(): bool
    {
        return $this->getStock() > 0;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function obtenerImagen(): string
    {
        return $this->getImagen();
    }
}
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

        $id = self::enteroEnRango($datos, 'id', 1);
        $stock = self::enteroEnRango($datos, 'stock', 0);
        $precio = self::precioValido($datos);

        return new self(
            $id,
            self::textoRequerido($datos, 'nombre'),
            new Categoria(self::textoRequerido($datos, 'categoria')),
            $precio,
            self::textoRequerido($datos, 'descripcion'),
            $stock,
            self::textoRequerido($datos, 'imagen')
        );
    }

    private static function textoRequerido(array $datos, string $campo): string
    {
        $valor = $datos[$campo];
        if (!is_string($valor) || trim($valor) === '') {
            throw new InvalidArgumentException("El campo {$campo} debe ser texto no vacío.");
        }

        return $valor;
    }

    private static function enteroEnRango(array $datos, string $campo, int $minimo): int
    {
        $valor = $datos[$campo];
        if (!is_int($valor) && !is_string($valor)) {
            throw new InvalidArgumentException("El campo {$campo} debe ser un entero.");
        }

        $entero = filter_var($valor, FILTER_VALIDATE_INT);
        if ($entero === false || $entero < $minimo) {
            throw new InvalidArgumentException("El campo {$campo} debe ser un entero mayor o igual a {$minimo}.");
        }

        return $entero;
    }

    private static function precioValido(array $datos): float
    {
        $valor = $datos['precio'];
        if (!is_int($valor) && !is_float($valor) && !is_string($valor)) {
            throw new InvalidArgumentException('El campo precio debe ser numérico.');
        }

        $precio = filter_var($valor, FILTER_VALIDATE_FLOAT);
        if ($precio === false || $precio < 0) {
            throw new InvalidArgumentException('El campo precio debe ser un número mayor o igual a cero.');
        }

        return $precio;
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
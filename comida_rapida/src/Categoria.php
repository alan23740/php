<?php 
declare(strict_types=1);
class Categoria{
    private string $nombre;
    public function __construct(string $nombre){
        $this->nombre = $nombre;
    }
    public function getNombre(): string{
        return $this->nombre;
    }
}
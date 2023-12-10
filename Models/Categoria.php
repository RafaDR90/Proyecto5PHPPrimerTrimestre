<?php
namespace Models;

use Utils\ValidationUtils;

class Categoria{
    private int|null $id;
    private string $nombre;

    public function __construct(int|null $id=null, string $nombre="")
    {
        $this->id=$id;
        $this->nombre=$nombre;
    }

    /**
     * Crea un array de objetos Categoria a partir de un array
     * @param array $data
     * @return array|null Devuelve un array de objetos Categoria
     */
    public static function fromArray(array $data):?array{
        $categorias=[];
        foreach ($data as $dt){
            $categoria= new Categoria(
                $dt['id']?? null,
                $dt['nombre']??'',
            );
            $categorias[]=$categoria;
        }
        return $categorias;
    }


    /**
     * Sanea y valida los datos de la categoria y devuelve un array con los datos saneados y validados
     * @param $nombre string
     * @return false|string
     */
    public function SaneaYValidaCategoria(string $nombre): bool|string{
        $nombre=ValidationUtils::sanidarStringFiltro($nombre);
        if(!ValidationUtils::noEstaVacio($nombre)) return false;
        if(!ValidationUtils::son_letras($nombre)) return false;
        if(!ValidationUtils::TextoNoEsMayorQue($nombre,40)) return false;
        return $nombre;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDb(): BaseDatos
    {
        return $this->db;
    }

    public function setDb(BaseDatos $db): void
    {
        $this->db = $db;
    }



}

<?php
namespace Models;
use Lib\BaseDatos,
    PDO;

class Categoria{
    private int|null $id;
    private string $nombre;
    private BaseDatos $db;

    public function __construct(int|null $id=null, string $nombre="")
    {
        $this->db=new BaseDatos();
        $this->id=$id;
        $this->nombre=$nombre;
    }

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

    public function getAll():?array
    {
        $select=$this->db->prepara("SELECT * FROM categorias");
        $select->execute();
        $categorias=$select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select=null;
        return $categorias;
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

<?php
namespace Models;
use Lib\BaseDatos,
PDO;

class Entrada{
    private int|null $id;
    private ?int $usuario_id;
    private ?int $categoria_id;
    private string $titulo;
    private string $descripcion;
    private string $date;
    private BaseDatos $db;

    public function __construct(int|null $id=null, ?int $usuario_id=null, ?int $categoria_id=null,
                                string $titulo="", string $descripcion="", string $date="")
    {
    $this->db=new BaseDatos();
    $this->id=$id;
    $this->usuario_id=$usuario_id;
    $this->categoria_id=$categoria_id;
    $this->titulo=$titulo;
    $this->descripcion=$descripcion;
    $this->date=$date;
    }

    public static function fromArray(array $data):?array{
        $entradas=[];
        foreach ($data as $dt){
        $entrada= new Entrada(
            $dt['id']?? null,
            $dt['usuario_id']??null,
            $dt['categoria_id']??null,
            $dt['titulo']??'',
            $dt['descripcion']??'',
            $dt['fecha']??'',
        );
        $entradas[]=$entrada;
        }
        return $entradas;
    }

    //public function getAll():?array
    //{
    //    $select=$this->db->prepara("SELECT * FROM entradas");
    //    $select->execute();
    //    $entradas=$select->fetchAll(PDO::FETCH_ASSOC);
    //    $select->closeCursor();
    //    $select=null;
    //    return $entradas;
    //}

    public function getAllById(int $id){
        $select=$this->db->prepara("SELECT * FROM entradas WHERE categoria_id=:id");
        $select->bindValue('id',$id);
        $select->execute();
        $entradas=$select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select=null;
        return $entradas;
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(?int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getCategoriaId(): ?int
    {
        return $this->categoria_id;
    }

    public function setCategoriaId(?int $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
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

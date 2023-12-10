<?php
namespace Models;

use Utils\ValidationUtils;

class Entrada{
    private int|null $id;
    private ?int $usuario_id;
    private ?int $categoria_id;
    private string $titulo;
    private string $descripcion;
    private string $date;

    public function __construct(int|null $id=null, ?int $usuario_id=null, ?int $categoria_id=null,
                                string $titulo="", string $descripcion="", string $date="")
    {
    $this->id=$id;
    $this->usuario_id=$usuario_id;
    $this->categoria_id=$categoria_id;
    $this->titulo=$titulo;
    $this->descripcion=$descripcion;
    $this->date=$date;
    }

    /**
     * Crea un objeto Entrada a partir de un array de datos
     * @param array $data Array de datos
     * @return Entrada Devuelve un objeto Entrada
     */
    public static function fromArrayOne(array $data):?Entrada{
        return $entrada= new Entrada(
                $data['id']?? null,
                $data['usuario_id']??null,
                $data['categoria_id']??null,
                $data['titulo']??'',
                $data['descripcion']??'',
                $data['fecha']??'',
        );
    }

    /**
     * Crea un array de objetos Entrada a partir de un array
     * @param array $data Array asociativo de datos
     * @return array|null Devuelve un array de objetos Entrada
     */
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

    /**
     * Sanea y valida los datos de la entrada y devuelve un array con los datos saneados y validados
     * @return array|null Devuelve un array con los datos saneados y validados o null si no se han podido sanear y validar
     */
    public function saneaYvalidaDatos(): ?array{
        if (isset($_POST['entrada'])){
            $titulo=$_POST['entrada']['titulo'];
            $descripcion=$_POST['entrada']['descripcion'];
            unset($_POST['entrada']);
            $titulo=ValidationUtils::sanidarStringFiltro($titulo);
            if (!ValidationUtils::noEstaVacio($titulo)) return null;
            if (!ValidationUtils::son_letras_y_numeros($titulo)) return null;
            if (!ValidationUtils::TextoNoEsMayorQue($titulo,30)) return null;
            $descripcion=ValidationUtils::sanidarStringFiltro($descripcion);
            if (!ValidationUtils::noEstaVacio($descripcion)) return null;
            if (!ValidationUtils::TextoNoEsMayorQue($descripcion,255)) return null;
            return ['titulo'=>$titulo,'descripcion'=>$descripcion];
        }else{
            $_SESSION['error']='Lo sentimos, parece que a habido alg&uacute;n problema';
            header('Location:'.BASE_URL);
            exit();
        }
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

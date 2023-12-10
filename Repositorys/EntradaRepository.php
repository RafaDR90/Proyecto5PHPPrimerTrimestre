<?php
namespace Repositorys;
use Lib\BaseDatos,
    PDO,
    PDOException;

class EntradaRepository{
    private BaseDatos $db;

    public function __construct() {
        $this->db=new BaseDatos();
    }

    /**
     * Cierra la conexion con la base de datos
     * @return void No devuelve nada
     */
    public function desconecta(): void{
        $this->db->cierraConexion();
    }

    /**
     * Añade una entrada a la base de datos y devuelve null si se ha añadido correctamente o un string con el error
     * @param $datos object Objeto con los datos de la entrada
     * @return string|null Devuelve null si se ha añadido correctamente o un string con el error
     */
    public function addEntrada(object $datos):?string{
        try {
            $ins=$this->db->prepara("INSERT INTO entradas (usuario_id,categoria_id,titulo,descripcion,fecha) values 
                                                                            (:usuario_id,:categoria_id,:titulo,:descripcion,:fecha)");
            $ins->bindValue(':usuario_id',$_SESSION['identificado']->id);
            $ins->bindValue(':categoria_id',$_SESSION['categoriaId']);
            $ins->bindValue(':titulo',$datos->getTitulo());
            $ins->bindValue(':descripcion',$datos->getDescripcion());
            $ins->bindValue(':fecha',date('Y-m-d H:i:s'));
            $ins->execute();
            $this->desconecta();
            return null;
        }catch (PDOException $err){
            return $err->getMessage();
        }
    }


    /**
     * Edita una entrada de la base de datos y devuelve null si se ha editado correctamente o un string con el error
     * @param $entrada object Objeto con los datos de la entrada
     * @return string|null Devuelve null si se ha añadido correctamente o un string con el error
     */
    public function editEntrada(object $entrada):?string{
        try {
            $update=$this->db->prepara("UPDATE entradas SET titulo=:titulo,descripcion=:descripcion WHERE id=:id");
            $update->bindValue(':titulo',$entrada->getTitulo());
            $update->bindValue(':descripcion',$entrada->getDescripcion());
            $update->bindValue(':id',$entrada->getId());
            $update->execute();
            $error= null;
        }catch (PDOException $err){
            $error= $err->getMessage();
        }
        return $error;
    }

    /**
     * Borra una entrada de la base de datos con una id especifica y devuelve null si se ha borrado correctamente o un
     * string con el error
     * @param $id int Id de la entrada a borrar
     * @return string|null Devuelve null si se ha borrado correctamente o un string con el error
     */
    public function deleteEntrada(int $id):?string{
        try {
            $delete=$this->db->prepara("DELETE FROM entradas WHERE id=:id");
            $delete->bindValue(':id',$id);
            $delete->execute();
            $error= null;
        }catch (PDOException $err){
            $error= $err->getMessage();
        }
        return $error;
    }


    /**
     * Obtiene todas las entradas de la base de datos con categoriaId especifica y las devuelve en un array de o null si no hay entradas
     * @param int $id Id de la categoria de la que se quieren obtener las entradas
     * @return array|null Devuelve un array de objetos Entrada
     */
    public function getAllById(int $id):?array{
           try{
                $select=$this->db->prepara("SELECT * FROM entradas WHERE categoria_id=:id");
                $select->bindValue(':id',$id);
                $select->execute();
                $entradas=$select->fetchAll(PDO::FETCH_ASSOC);
                $this->desconecta();
           }catch (PDOException $err){
               $entradas= null;
           }
               return $entradas;
    }

    /**
     * Obtiene todas las entradas de la base de datos con id especifica y las devuelve en un array  o null si no hay entradas
     * @param $id int Id de la categoria de la que se quieren obtener las entradas
     * @return mixed|string Devuelve un array de objetos Entrada
     */
    public function entradaConId($id): mixed{
        try {
            $select=$this->db->prepara("SELECT * FROM entradas WHERE id=:id");
            $select->bindValue(':id',$id);
            $select->execute();
            $entrada=$select->fetch(PDO::FETCH_ASSOC);
            $this->desconecta();
            return $entrada;
        }catch (PDOException $err){
            return $err->getMessage();
        }
    }
}
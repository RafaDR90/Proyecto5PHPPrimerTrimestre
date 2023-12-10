<?php
namespace Repositorys;
use Lib\BaseDatos,
    PDO,
    PDOException;
use Models\Categoria;

class CategoriaRepository{
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
     * Añade una categoria a la base de datos y devuelve null si se ha añadido correctamente o un string con el error
     * @param $nombre string Nombre de la categoria a añadir
     * @return string|null Devuelve null si se ha añadido correctamente o un string con el error
     */
    public function addCategoria(string $nombre):?string{
        try{
            $ins=$this->db->prepara("INSERT INTO categorias (nombre) values (:nombre)");
            $ins->bindValue(':nombre',$nombre);
            $ins->execute();
            $resultado=null;
        }catch (PDOException $err){
            $resultado=$err->getMessage();
        }
        $ins->closeCursor();
        $this->desconecta();
        return $resultado;
    }

    /**
     * Borra una categoria de la base de datos y devuelve null si se ha borrado correctamente o un string con el error
     * @param int $id Id de la categoria a borrar
     * @return string|null Devuelve null si se ha borrado correctamente o un string con el error
     */
    public function borrarCategoriaPorId(int $id):?string{
        $categoria = new Categoria();
        try{
            $delete=$this->db->prepara("DELETE FROM categorias WHERE id = :id");
            $delete->bindValue(':id',$id);
            $delete->execute();
            $resultado=null;
        }catch (PDOException $e){
            $resultado=$e->getMessage();
        }
        $delete->closeCursor();
        $this->desconecta();
        return $resultado;
    }

    /**
     * Actualiza una categoria de la base de datos y devuelve null si se ha actualizado correctamente o un string con el error
     * @param $datos Categoria Categoria a actualizar
     * @return string|null Devuelve null si se ha actualizado correctamente o un string con el error
     */
    public function update(Categoria $datos):?string{
        $resultado='';
        try{
            $update=$this->db->prepara("UPDATE categorias SET nombre = :nombre WHERE id = :id");
            $update->bindValue(':id',$datos->getId());
            $update->bindValue(':nombre',$datos->getNombre());
            $update->execute();
            $resultado=null;
        }catch (PDOException $e){
            $resultado=$e->getMessage();
        }
        $this->desconecta();

        return $resultado;
    }

    /**
     * Obtiene una categoria de la base de datos por su id
     * @param $id int Id de la categoria a obtener
     * @return mixed|null Devuelve un array con los datos de la categoria o null si no se ha encontrado
     */
    public function obtenerCategoriaPorID(int $id):?array{
        try{
            $select=$this->db->prepara("SELECT * FROM categorias WHERE id = :id");
            $select->bindValue(':id',$id);
            $select->execute();
            $resultado=$select->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            $resultado=null;
        }
        $select->closeCursor();
        $this->desconecta();
        return $resultado;
    }

    /**
     * Obtiene todas las categorias de la base de datos y las devuelve en un array
     * @return array|null Devuelve un array con todas las categorias o null si no se han encontrado
     */
    public function getAll():?array
    {
        try{
            $select=$this->db->prepara("SELECT * FROM categorias");
            $select->execute();
            $categorias=$select->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            $categorias=null;
        }
        $select->closeCursor();
        $this->desconecta();
        return $categorias;

    }
}
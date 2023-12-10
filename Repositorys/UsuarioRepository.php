<?php
namespace Repositorys;
use Lib\BaseDatos,
    PDO,
    PDOException;
class UsuarioRepository{
    private BaseDatos $db;

    public function __construct()
    {
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
     * Crear un usuario en la base de datos y devuelve Null si se ha añadido correctamente o un string con el error
     * @param $usuario object Objeto con los datos del usuario
     * @return string|null Devuelve null si se ha añadido correctamente o un string con el error
     */
    public function create(object $usuario): ?string{

        $id=null;
        $nombre=$usuario->getNombre();
        $apellidos=$usuario->getApellidos();
        $email=$usuario->getEmail();
        $password=$usuario->getPassword();
        $rol='user';
        try{
            $ins=$this->db->prepara("INSERT INTO usuarios (id,nombre,apellidos,email,password,rol) values (:id,:nombre,:apellidos,:email,:password,:rol)");
            $ins->bindValue(':id',$id);
            $ins->bindValue(':nombre',$nombre);
            $ins->bindValue(':apellidos',$apellidos);
            $ins->bindValue(':email',$email);
            $ins->bindValue(':password',$password);
            $ins->bindValue(':rol',$rol);
            $ins->execute();
            $result=null;
        }catch (PDOException $err){
            $result=$err->getMessage();
        }
        $this->desconecta();
        return $result;
    }

    /**
     * Devuelve un array de objetos Usuario si esta el email. Si no esta el email devuelve false
     * @param $email string Email del usuario
     * @return false|mixed Devuelve un objeto Usuario o false si no lo encuentra
     */
    public function buscaMail(string $email): mixed{
        try{
            $cons=$this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
            $cons->bindValue(':email',$email,PDO::PARAM_STR);
            $cons->execute();
            if ($cons && $cons->rowCount()==1){
                $result=$cons->fetch(PDO::FETCH_OBJ);
            }else{
                $result=false;
            }
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }

    /**
     * Obtiene todos los id y nombres de los usuarios de la base de datos y los devuelve en un array de objetos Usuario o false si no lo encuentra
     * @return array|false Devuelve un array de objetos Usuario o false si no lo encuentra
     */
    public function getAllNameAndId(): mixed{
        try{
            $select=$this->db->prepara("SELECT id,nombre FROM usuarios");
            $select->execute();
            if ($select && $select->rowCount()>0){
                $result=$select->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $result=false;
            }
            $this->desconecta();
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }

    /**
     * Edita el password de un usuario de la base de datos y devuelve true si se ha editado correctamente o false si no se ha podido editar
     * @param $id int Id del usuario a obtener
     * @param $password string Password del usuario a obtener
     * @return bool Devuelve true si el usuario existe o false si no existe
     */
    public function editarPassword(int $id,string $password): bool{
        try{
            $update=$this->db->prepara("UPDATE usuarios SET password=:password WHERE id=:id");
            $update->bindValue(':password',$password);
            $update->bindValue(':id',$id);
            $update->execute();
            if ($update && $update->rowCount()==1){
                $result=true;
            }else{
                $result=false;
            }
            $this->desconecta();
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }

    /**
     * Edita un dato de un usuario de la base de datos y devuelve true si se ha editado correctamente o false si no se ha podido editar
     * @param $id int Id del usuario a editar
     * @param $dato string Dato a editar
     * @param $valor string Valor del dato a editar
     * @return bool Devuelve true si se ha editado correctamente o false si no se ha podido editar
     */
    public function editarDato(int $id,string $dato,string $valor): bool{
        try{
            $update=$this->db->prepara("UPDATE usuarios SET $dato=:valor WHERE id=:id");
            $update->bindValue(':valor',$valor);
            $update->bindValue(':id',$id);
            $update->execute();
            if ($update && $update->rowCount()==1){
                $result=true;
            }else{
                $result=false;
            }
            $this->desconecta();
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }

    /**
     * Edita los datos de un usuario de la base de datos y devuelve true si se ha editado correctamente o false si no se ha podido editar
     * @param $id int Id del usuario a editar
     * @param array $datos Datos a editar
     * @return bool Devuelve true si se ha editado correctamente o false si no se ha podido editar
     */
    public function editarDatos(int $id, array $datos): bool{
        try{
            $update=$this->db->prepara("UPDATE usuarios SET nombre=:nombre,apellidos=:apellidos,email=:email WHERE id=:id");
            isset($datos['nombre'])?$nombre=$datos['nombre']:$nombre=$_SESSION['identificado']->nombre;
            $update->bindValue(':nombre',$nombre);
            isset($datos['apellidos'])?$apellidos=$datos['apellidos']:$apellidos=$_SESSION['identificado']->apellidos;
            $update->bindValue(':apellidos',$apellidos);
            isset($datos['email'])?$email=$datos['email']:$email=$_SESSION['identificado']->email;
            $update->bindValue(':email',$email);
            $update->bindValue(':id',$id);
            $update->execute();
            if ($update->rowCount()==1){
                $result=true;
            }else{
                $result=false;
            }
            $this->desconecta();
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }
}
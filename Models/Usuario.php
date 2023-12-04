<?php
namespace Models;
use Lib\BaseDatos,
    PDO,
    PDOException;
class Usuario{
    private string|null $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;

    protected array $errores;
    private BaseDatos $db;

    public function __construct(int|null $id=null, string $nombre='', string $apellidos='', string $email='', string $password='', string $rol='')
    {
        $this->db=new BaseDatos();
        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->email=$email;
        $this->password=$password;
        $this->rol=$rol;
    }

    public function getAllNameAndId():?array
    {
        $select=$this->db->prepara("SELECT id,nombre FROM usuarios");
        $select->execute();
        $categorias=$select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select=null;
        return $categorias;
    }
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public static function fromArray(array $data):?array{
        $usuarios=[];
        foreach ($data as $dt){
            $user= new Usuario(
                $dt['id']?? null,
                $dt['nombre']??'',
                $dt['apellidos']??'',
                $dt['email']??'',
                $dt['password']??'',
                $dt['rol']??'',
            );
            $usuarios[]=$user;
        }
        return $usuarios;
    }

    public function save(){
        if ($this->getId()){
            return $this->update();
        }else{
            return $this->create();
        }
    }

    public function desconecta(){
        $this->db->cierraConexion();
    }

    public function create(){
        $id=null;
        $nombre=$this->getNombre();
        $apellidos=$this->getApellidos();
        $email=$this->getEmail();
        $password=$this->getPassword();
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
            $result=true;
        }catch (PDOException $err){
            $result=false;
        }
        return $result;
    }

    public function obtenerPassword($email){
        $select= $this->db->prepara("SELECT * FROM usuarios WHERE email = :email");
        $select->bindValue(':email', $email);
        $select->execute();
        return $resultados = $select->fetch(PDO::FETCH_ASSOC);
    }

    public function login(){
        $result=false;
        $email = $this->getEmail();
        $password=$this->getPassword();
        $usuario=$this->buscaMail($email);
        if ($usuario !==false){
            $verify=password_verify($password,$usuario->password);
            if ($verify){
                return $usuario;
            }
        }

    }
    public function buscaMail($email){
        $cons=$this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $cons->bindValue(':email',$email,PDO::PARAM_STR);
        try{
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
    public function validar(){}

    public function sanear(){}

}
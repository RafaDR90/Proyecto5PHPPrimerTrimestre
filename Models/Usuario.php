<?php
namespace Models;

use Utils\ValidationUtils;

class Usuario{
    private string|null $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;

    protected array $errores;

    public function __construct(int|null $id=null, string $nombre='', string $apellidos='', string $email='', string $password='', string $rol='')
    {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->email=$email;
        $this->password=$password;
        $this->rol=$rol;
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

    /**
     * Devuelve un array de objetos Usuario a partir de un array asociativo
     * @param array $data Array asociativo de datos
     * @return array Devuelve un array de objetos Usuario
     */
    public static function fromArray(array $data):?array{
        $usuarios=[];
        foreach ($data as $dt){
            $user= new Usuario(
                $dt['id']?? null,
                $dt['nombre']??'',
                $dt['apellidos']??'',
                $dt['email']??'',
                $dt['password']??'',
                $dt['rol']??'user',
            );
            $usuarios[]=$user;
        }
        return $usuarios;
    }

    /**
     * Crea un objeto Usuario a partir de un array asociativo
     * @param array $data Array asociativo de datos
     * @return Usuario|null Devuelve un objeto Usuario o null si no se ha podido crear
     */
    public static function fromArrayOne(array $data):?Usuario{
        return new Usuario(
            $data['id']?? null,
            $data['nombre']??'',
            $data['apellidos']??'',
            $data['email']??'',
            $data['password']??'',
            'user',
        );
    }


    /**
     * Sanea y valida los datos del usuario y devuelve un array con los datos saneados y validados
     *
     * @param $datosConfirmados mixed Array de datos confirmados o false si no se han podido confirmar
     * @return mixed
     */
    public function login(mixed $datosConfirmados):mixed{
        $password=$this->getPassword();

        if ($datosConfirmados !==false){
            $verify=password_verify($password,$datosConfirmados->password);
            if ($verify){
                return $datosConfirmados;
            }else return null;
        }else return null;

    }

    /**
     * Sanea y valida los datos del usuario y devuelve un array con los datos saneados y validados
     * @param array $datos Array de datos
     * @return array|null Devuelve un array con los datos saneados y validados o null si no se han podido sanear y validar
     */
    public function SaneaDatos(array $datos):?array{
        $datos['nombre']=ValidationUtils::sanidarStringFiltro($datos['nombre']);
        $datos['apellidos']=ValidationUtils::sanidarStringFiltro($datos['apellidos']);
        $datos['email']=filter_var($datos['email'],FILTER_SANITIZE_EMAIL);
        $datos['password']=ValidationUtils::sanidarStringFiltro($datos['password']);
        return $datos;
    }

    /**
     * Valida los datos del usuario y devuelve un string con el error o null si no hay errores
     * @param $datos array Array de datos
     * @return string|null Devuelve un string con el error o null si no hay errores
     */
    public function ValidaDatos(array $datos):?string{
        //Valida Nombre
        if (!ValidationUtils::noEstaVacio($datos['nombre'])){
            return 'El nombre no puede estar vacio';
        }
        if (!ValidationUtils::son_letras($datos['nombre'])){
            return 'El nombre no puede contener caracteres especiales ni numeros';
        }
        if (!ValidationUtils::TextoNoEsMayorQue($datos['nombre'],30)){
            return 'El nombre no puede tener mas de 30 caracteres';
        }
        //Valida Apellidos
        if (!ValidationUtils::noEstaVacio($datos['apellidos'])){
            return 'Los apellidos no pueden estar vacios';
        }
        if (!ValidationUtils::son_letras($datos['apellidos'])){
            return 'Los apellidos no pueden contener caracteres especiales ni numeros';
        }
        if (!ValidationUtils::TextoNoEsMayorQue($datos['apellidos'],50)){
            return 'Los apellidos no pueden tener mas de 50 caracteres';
        }
        //Valida Email
        if (!filter_var($datos['email'],FILTER_VALIDATE_EMAIL)){
            return 'El email no es valido';
        }
        //Valida Password
        if (!ValidationUtils::noEstaVacio($datos['password'])){
            return 'La contraseña no puede estar vacia';
        }
        if(!ValidationUtils::validarContrasena($datos['password'])){
            return 'La contraseña no es valida';
        }
        return null;
    }

}
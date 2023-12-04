<?php
namespace Controllers;
use Lib\Pages,
    Models\Usuario;
use Utils\Utils;

class UsuarioController{
    private Pages $pages;

    public function __construct()
    {
        $this->pages=new Pages();
    }

    public function identifica(){
        $this->pages->render("usuario/login");
    }
//ACTUALMENTE ESTOY HACIENDO EL LOGIN          ACTUALMENTE ESTOY HACIENDO EL LOGIN          ACTUALMENTE ESTOY HACIENDO EL LOGIN
    public function login(){
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            if ($_POST['data']){
                $login=$_POST['data'];

                $usuario=Usuario::fromArray($login);
                $identity=$usuario->login();

                if($identity && is_object($identity)){
                    $_SESSION['identificado']=$identity;
                    if ($identity->rol == 'admin'){
                        $_SESSION['admin']=true;
                        header("Location: " . BASE_URL );
                    }
                }else{
                    $_SESSION['error_login']='identificacion fallida';
                    $this->pages->render('Usuario/login');
                }
                $usuario->desconecta();
            }
        }
        $this->pages->render("Usuario/Login");
    }

    public function registro(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if ($_POST['data']){
                $registrado=$_POST['data'];

                //sanear y validar con metodos estaticos

                $registrado['password']=password_hash($registrado['password'],PASSWORD_BCRYPT,['cost'=>4]);

                //tambien se puede validar en la funcion fromArray
                $usuario=Usuario::fromArray($registrado);

                $save=$usuario->save();
                if ($save){
                    $_SESSION['register']='complete';
                }else {
                    $_SESSION['register'] = 'failed';
                }
            }else{
                $_SESSION['register'] = 'failed';
            }
            $usuario->desconecta();
        }
        $this->pages->render('Usuario/Registro');
    }

    public function logout(){
        utils::deleteSession('identificado');
        header("Location: " . BASE_URL);
    }
}
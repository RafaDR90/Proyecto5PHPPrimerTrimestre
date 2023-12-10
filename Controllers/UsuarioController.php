<?php
namespace Controllers;
use Lib\Pages,
    Models\Usuario;
use Services\UsuarioService;
use Utils\Utils,
    Utils\ValidationUtils;

class UsuarioController{
    private Pages $pages;
    private UsuarioService $usuarioService;
    private Usuario $usuario;

    public function __construct()
    {
        $this->pages=new Pages();
        $this->usuarioService=new UsuarioService();
        $this->usuario=new Usuario();
    }

    /**
     * Muestra la vista de login
     * @return void redirige a la pagina principal si ya esta identificado de lo contrario muestra la vista de login
     */
    public function identifica():void{
        if (isset($_SESSION['identificado'])){
            header("Location: " . BASE_URL);
            exit();
        }
        $this->pages->render("usuario/login");
    }

    /**
     * Comprueba si hay datos POST y si los hay los sanea y valida, si son correctos busca el usuario en la base de datos
     * y si lo encuentra crea la sesion y redirige a la pagina principal, si no hay datos POST o no son correctos muestra
     * la vista de login
     * @return void redirige a la pagina principal si los datos son correctos de lo contrario muestra la vista de login
     */
    public function login():void{
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            if ($_POST['data']){
                if (session_status()==PHP_SESSION_NONE){
                    session_start();
                }
                $loginData=$_POST['data'];

                $usuario=Usuario::fromArrayOne($loginData);
                $emailConfirmado=$this->usuarioService->buscaMail($usuario->getEmail());
                $identity=$usuario->login($emailConfirmado);
                if($identity && is_object($identity)){
                    $_SESSION['identificado']=$identity;
                    $_SESSION['exito']='Bienvenido '.ucwords(strtolower($_SESSION['identificado']->nombre));
                    header("Location: " . BASE_URL);
                }else{
                    $_SESSION['error_login']='identificacion fallida';
                    $this->pages->render('Usuario/login');
                }
            }
        }
    }

    /**
     * Muestra la vista de registro si no se ha enviado el formulario, si se ha enviado el formulario
     * lo sanea y valida y lo registra en la base de datos.
     * @return void Muestra la vista de registro o redirige a la pagina principal
     */
    public function registro():void{
        if (session_status()==PHP_SESSION_NONE){
            session_start();
        }
        if (isset($_SESSION['identificado'])){
            header("Location: " . BASE_URL);
            exit();
        }
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if (isset($_POST['data'])){
                $registrado=$this->usuario->SaneaDatos($_POST['data']);
                $error=$this->usuario->ValidaDatos($registrado);
                if ($error){
                    $this->pages->render('Usuario/Registro',['error'=>$error]);
                    exit();
                }
                $emailConfirmado=$this->usuarioService->buscaMail($registrado['email']);
                if ($emailConfirmado){
                    $this->pages->render('Usuario/Registro',['error'=>'El email ya esta en uso']);
                    exit();
                }
                $registrado['password']=password_hash($registrado['password'],PASSWORD_BCRYPT,['cost'=>4]);
                //tambien se puede validar en la funcion fromArray
                $registrado=Usuario::fromArrayOne($registrado);
                $error=$this->usuarioService->create($registrado);
                if ($error){
                    $this->pages->render('Usuario/Registro',['error'=>$error]);
                }else {
                    $_SESSION['exito'] = '&excl;Bienvenido, ya puedes iniciar sesion&excl;';
                    header("Location: " . BASE_URL );
                    exit();
                }
            }else{
                $_SESSION['error'] = 'Registro fallido, introduzca bien los datos';
            }
        }
        $this->pages->render('Usuario/Registro');
    }

    /**
     * Cierra la sesion y redirige a la pagina principal
     * @return void redirige a la pagina principal
     */
    public function logout(){
        if (session_status()==PHP_SESSION_NONE){
            session_start();
        }
        if (isset($_SESSION['identificado'])){
            Utils::deleteSession('identificado');
        }

        header("Location: " . BASE_URL);
    }

    /**
     * Muestra la vista de editar usuario si se ha enviado el formulario, si se ha enviado el formulario
     * lo sanea y valida y lo edita en la base de datos.
     * @return void Muestra la vista de editar usuario o redirige a la pagina principal
     */
    public function editar():void{
        if (session_status()==PHP_SESSION_NONE){
            session_start();
        }
            if ($_SESSION['identificado']){
                if ($_SERVER['REQUEST_METHOD']=='POST'){
                    if (isset($_POST['password'])){
                        if ($_POST['password']['password1']==$_POST['password']['password2']) {
                            $password = $_POST['password']['password1'];
                            $password=ValidationUtils::sanidarStringFiltro($password);
                            if(!ValidationUtils::TextoNoEsMayorQue($password, 30)){
                                $this->pages->render('Usuario/EditarView',['error'=>'La contraseña no puede tener mas de 30 caracteres',
                                                                           'editaPassword'=>'true']);
                                exit();
                            }
                            if (!ValidationUtils::validarContrasena($password)){
                                $this->pages->render('Usuario/EditarView',['error'=>'La contraseña no es valida',
                                                                           'editaPassword'=>'true']);
                                exit();
                            }
                            $password=password_hash($password,PASSWORD_BCRYPT,['cost'=>4]);
                            $update=$this->usuarioService->editarPassword($_SESSION['identificado']->id,$password);
                            if ($update) {
                                $_SESSION['identificado']->password = $password;
                                $this->pages->render('Usuario/EditarView',['editado'=>'Contraseña editada correctamente']);
                            }else{
                                $this->pages->render('Usuario/EditarView',['error'=>'Ha habido un error al editar la contraseña',
                                                                           'editaPassword'=>'true']);
                            }
                        }else{
                            $this->pages->render('Usuario/EditarView',['noCoincide'=>'true',
                                                                       'editaPassword'=>'true']);
                        }
                    }elseif(isset($_POST['datos'])){
                        $editar=[];
                        if ($_POST['datos']['nombre']!=$_SESSION['identificado']->nombre){
                            $nuevoNombre=ValidationUtils::sanidarStringFiltro($_POST['datos']['nombre']);
                            if (!ValidationUtils::noEstaVacio($nuevoNombre)){
                                $this->pages->render('Usuario/EditarView',['error'=>'El nombre no puede estar vacio',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            if(!ValidationUtils::son_letras($nuevoNombre)){
                                $this->pages->render('Usuario/EditarView',['error'=>'El nombre no puede contener caracteres especiales',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            if (!ValidationUtils::TextoNoEsMayorQue($nuevoNombre, 30)){
                                $this->pages->render('Usuario/EditarView',['error'=>'El nombre no puede tener mas de 30 caracteres',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            $editar['nombre']=$nuevoNombre;
                        }
                        if ($_POST['datos']['apellidos']!=$_SESSION['identificado']->apellidos){
                            $nuevoApellido=ValidationUtils::sanidarStringFiltro($_POST['datos']['apellidos']);
                            if (!ValidationUtils::noEstaVacio($nuevoApellido)){
                                $this->pages->render('Usuario/EditarView',['error'=>'Los apellidos no pueden estar vacios',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            if(!ValidationUtils::son_letras($nuevoApellido)){
                                $this->pages->render('Usuario/EditarView',['error'=>'Los apellidos no pueden contener caracteres especiales',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            if (!ValidationUtils::TextoNoEsMayorQue($nuevoApellido, 30)){
                                $this->pages->render('Usuario/EditarView',['error'=>'Los apellidos no pueden tener mas de 30 caracteres',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }

                            $editar['apellidos']=$nuevoApellido;
                        }
                        if ($_POST['datos']['email']!=$_SESSION['identificado']->email){
                            $nuevoEmail=filter_var($_POST['datos']['email'],FILTER_SANITIZE_EMAIL);
                            if (!filter_var($nuevoEmail,FILTER_VALIDATE_EMAIL)){
                                $this->pages->render('Usuario/EditarView',['error'=>'El email no es valido',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            $emailConfirmado=$this->usuarioService->buscaMail($nuevoEmail);
                            if ($emailConfirmado){
                                $this->pages->render('Usuario/EditarView',['error'=>'El email ya esta en uso',
                                                                           'editaDatos'=>'true']);
                                exit();
                            }
                            $editar['email']=$nuevoEmail;
                        }
                        if (count($editar)==0){
                            $this->pages->render('Usuario/EditarView',['editaDatos'=>'true']);
                            exit();
                        }elseif(count($editar)==1){
                            $resultado=$this->usuarioService->editarDato($_SESSION['identificado']->id,array_keys($editar)[0],array_values($editar)[0]);
                            if ($resultado){
                                $_SESSION['identificado']->{array_keys($editar)[0]}=array_values($editar)[0];
                                $this->pages->render('Usuario/EditarView',['editado'=>'Dato editado correctamente']);
                            }
                        }elseif (count($editar)>1){
                            $resultado=$this->usuarioService->editarDatos($_SESSION['identificado']->id,$editar);
                            if ($resultado){
                                foreach ($editar as $key=>$value){
                                    $_SESSION['identificado']->$key=$value;
                                }
                                $this->pages->render('Usuario/EditarView',['editado'=>'Datos editados correctamente']);
                            }else{
                                $this->pages->render('Usuario/EditarView',['error'=>'Ha habido un error al editar los datos',
                                                                           'editaDatos'=>'true']);
                            }
                        }
                    } else{
                        $_SESSION['error']='Parece que ha habido algun error.';
                        header('Location:'.BASE_URL);
                    }
                }elseif(isset($_GET['editaPassword'])){
                    $this->pages->render('Usuario/EditarView',['editaPassword'=>'true']);
                } elseif(isset($_GET['editDatos'])){
                    $this->pages->render('Usuario/EditarView', ['editaDatos' => 'true']);
                }else{
                    $this->pages->render('Usuario/EditarView');
                }

        }else{
            $_SESSION['error']='Debe identificarse para editar su cuenta.';
            header('Location:'.BASE_URL);
            }
    }
}
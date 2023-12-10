<?php
namespace Controllers;
use Lib\Pages,
Models\Entrada;
use Models\Usuario;
use Services\UsuarioService;
use Utils\Utils,
    Utils\ValidationUtils,
    Services\EntradaService;

class EntradaController{
    private Pages $pages;
    private Entrada $Entrada;
    private EntradaService $EntradaService;
    private UsuarioService $UsuarioService;
    public function __construct()
    {
        $this->pages=new Pages();
        $this->Entrada=new Entrada();
        $this->EntradaService=new EntradaService();
    }

    /**
     * Obtiene todas las entradas de una categoria por su id y las devuelve en un array de objetos Entrada
     * @return array|void|null
     */
    public function obtenerEntradasPorId(): mixed{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['categoriaId']) && isset($_GET['categoriaNombre'])){
            $_SESSION['categoriaId']=$_GET['categoriaId'];
            $_SESSION['categoriaNombre']=$_GET['categoriaNombre'];
            header('Location:'.BASE_URL.'entrada/mostrarEntradasPorId/');
            exit();
        }elseif($_SESSION['categoriaId'] && $_SESSION['categoriaNombre']){
            $id=ValidationUtils::SVNumero($_SESSION['categoriaId']);
            if ($id!=null){
                $entradaArray=$this->EntradaService->getAllById($id);
                return Entrada::fromArray($entradaArray);
            }else{
                $_SESSION['error']='Lo sentimos, parece que a habido un problema al seleccionar la categoria';
                header('Location:'.BASE_URL);
                exit();
            }
        }else{
            $_SESSION['error']='Lo sentimos, parece que a habido un problema al seleccionar la categoria';
            header('Location:'.BASE_URL);
            exit();
        }
    }

    /**
     * Obtiene todos los usuarios de la base de datos y los devuelve en un array de objetos usuario
     * @return array|usuario|null
     */
    public function obtenerUsuarios(): mixed{
        $usuarioService=new UsuarioService();
        $usuariosArray=$usuarioService->getAllNameAndId();
        if ($usuariosArray==null){
            return null;
        }
        return Usuario::fromArray($usuariosArray);
    }

    public function mostrarEntradasPorId(){
        $entradas=$this->obtenerEntradasPorId();
            $this->pages->render('Entradas/EntradasView',['entradas'=>$entradas,
                                                          'usuarios'=>$this->obtenerUsuarios()]);
    }

    /**
     * Añade una entrada a la base de datos si se ha enviado el formulario de añadir entrada y estas identificado
     * Si no estas identificado te redirige a la pagina principal y si no se ha enviado el formulario te muestra el
     * formulario de añadir entrada
     * @return void Muestra la vista de añadir entrada o redirige a la pagina principal
     */
    public function addEntrada(): void{
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        //Comprueba si estas identificado y si se ha enviado el formulario de añadir entrada
        if (isset($_SESSION['identificado']) && isset($_POST['entrada'])){
            $datos=$this->Entrada->saneaYvalidaDatos();
            if ($datos!=null){
                $datos=Entrada::fromArrayOne($datos);
                $error=$this->EntradaService->addEntrada($datos);
                if ($error!=null){
                    $_SESSION['error']=$error;
                    header('Location:'.BASE_URL);
                }else{
                    $_SESSION['exito']='Entrada añadida correctamente';
                    header('Location:'.BASE_URL.'entrada/mostrarEntradasPorId/');
                    exit();
                }
            }else{
                $_SESSION['error']='Lo sentimos, parece que a habido un problema al a&ntilde;adir la entrada';
                header('Location:'.BASE_URL);
            }
        //En caso de que no se haya enviado el formulario de añadir entrada, comprueba si estas identificado y renderiza el formulario
        }elseif(isset($_SESSION['identificado'])){
            if(isset($_SESSION['categoriaId']) && isset($_SESSION['categoriaNombre'])) {
                $this->pages->render('Entradas/gestionEntradaView');
            }else{
                $_SESSION['error']='Lo sentimos, parece que a habido un problema al seleccionar la categoria';
                header('Location:'.BASE_URL);
            }
        }else{
            $_SESSION['error']='Debes identificarte para añadir una entrada';
            header('Location:'.BASE_URL);
        }
    }

    /**
     * Edita una entrada de la base de datos si se ha enviado el formulario de editar entrada y estas identificado
     * Si no estas identificado te redirige a la pagina principal y si no se ha enviado el formulario te muestra el
     * formulario de editar entrada
     * @return void
     */
    public function editEntrada(): void{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['identificado'])){
            if(isset($_POST['entrada'])){
                $entradaAEditar=Entrada::fromArrayOne($_POST['entrada']);
                $error=$this->EntradaService->editEntrada($entradaAEditar);
                if ($error!=null) {
                    $_SESSION['error'] = $error;
                    header('Location:' . BASE_URL);
                }else{
                    $_SESSION['exito']='Entrada editada correctamente';
                    $this->mostrarEntradasPorId();
                    exit();
                }
            }elseif(isset($_GET['idEntrada'])){
                $entrada=$this->EntradaService->entradaConId($_GET['idEntrada']);
                if (is_array($entrada)){
                    $this->pages->render('Entradas/gestionEntradaView',['entrada'=>$entrada]);
                    exit();
                }
            }
            $_SESSION['error']='Ha habido un problema seleccionando la entrada';
            header('Location:'.BASE_URL);

    }else{
            $_SESSION['error']='Debes identificarte para editar una entrada';
            header('Location:'.BASE_URL);
        }
    }

    /**
     * Elimina una entrada de la base de datos si se ha enviado el formulario de eliminar entrada y estas identificado
     * Si no estas identificado te redirige a la pagina principal y si no se ha enviado el formulario te muestra el
     * formulario de eliminar entrada
     * @return void Redirige a la pagina principal
     */
    public function deleteEntrada(): void{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['identificado'])){
            if (isset($_GET['idEntrada'])){
                $error=$this->EntradaService->deleteEntrada($_GET['idEntrada']);
                if ($error!=null){
                    $_SESSION['error']=$error;
                    header('Location:'.BASE_URL);
                }else{
                    $_SESSION['exito']='Entrada eliminada correctamente';
                    $this->mostrarEntradasPorId();
                    exit();
                }
            }else{
                $_SESSION['error']='Ha habido un problema seleccionando la entrada';
                header('Location:'.BASE_URL);
            }
        }else{
            $_SESSION['error']='Debes identificarte para eliminar una entrada';
            header('Location:'.BASE_URL);
        }
    }

}
<?php
namespace Controllers;
use Lib\Pages,
Models\Entrada;
use Models\Usuario;
use Utils\Utils,
    Utils\ValidationUtils;

class EntradaController{
    public function __construct()
    {
        $this->pages=new Pages();
        $this->Entrada=new Entrada();
    }

    public function obtenerEntradasPorId():?array{
        if (isset($_GET['idCategoria'])){
            $id=ValidationUtils::SVNumero($_GET['idCategoria']);
            if ($id!=null){
                $entradaArray=$this->Entrada->getAllById($id);
                //Llamo a la funcion javascript para que no se vea el id en la url
                echo '<script>window.history.replaceState({}, document.title, window.location.pathname);</script>';
                return Entrada::fromArray($entradaArray);
            }else{
                $this->pages->render('Categorias/CategoriasView',['errores'=>'Lo sentimos, parece que a habido un problema al seleccionar la categoria']);
                exit();
            }
        }else{
            $this->pages->render('Categorias/CategoriasView',['errores'=>'Lo sentimos, parece que a habido un problema al seleccionar la categoria']);
            exit();
        }
    }
    public function obtenerUsuarios(){
        $usuario=new Usuario();
        $usuariosArray=$usuario->getAllNameAndId();
        return Usuario::fromArray($usuariosArray);
    }

    public function mostrarEntradasPorId(){
        $entradas=$this->obtenerEntradasPorId();
            $this->pages->render('Entradas/EntradasView',['entradas'=>$entradas,
                                                          'usuarios'=>$this->obtenerUsuarios()]);
    }

}
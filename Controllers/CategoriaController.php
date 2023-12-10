<?php
namespace Controllers;
use Lib\Pages,
    Models\Categoria,
    Services\CategoriaService;

use Utils\Utils;

class CategoriaController{
    private Pages $pages;
    private Categoria $Categoria;
    private CategoriaService $CategoriaService;
    public function __construct()
    {
        $this->pages=new Pages();
        $this->Categoria=new Categoria();
        $this->CategoriaService=new CategoriaService();
    }

    /**
     * Obtiene todas las categorias de la base de datos y las devuelve en un array de objetos Categoria
     * @return array Devuelve un array de objetos Categoria
     */
    public function obtenerCategorias(): array{
        $categoriasArray=$this->CategoriaService->getAll();
        return Categoria::fromArray($categoriasArray);

    }


    /**
     * Muestra todas las categorias
     * @return void Muestra la vista de categorias
     */
    public function mostrarCategorias(){
        $this->pages->render('Categorias/CategoriasView',['categorias'=>$this->obtenerCategorias()]);
    }

    /**
     * Muestra la vista de editar categoria si no se ha enviado el formulario, si se ha enviado el formulario
     * lo sanea y valida y lo edita en la base de datos
     * @return void Muestra la vista de editar categoria o redirige a la pagina principal
     */
    public function editarCategoria(): void{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_POST['nombre'])){
            if (isset($_GET['idCategoria'])){
                $categorias=$this->CategoriaService->obtenerCategoriaPorID($_GET['idCategoria']);
                if($categorias==null){
                    $_SESSION['error']='Ha habido algun error al obtener la categoria';
                    header('Location:'.BASE_URL);
                    exit();
                }
                $this->pages->render('Categorias/GestionCategoria',['categoriaEdit'=>$categorias]);
            }else{
                $_SESSION['error']='No se ha encontrado la categoria';
                header('Location:'.BASE_URL);
            }
        }else{
            if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['nombre'])){
                $categoria=new Categoria();
                $categoriaEditada=new Categoria($_GET['idCategoria'],$_POST['nombre']);
                $errores=$this->CategoriaService->update($categoriaEditada);
                if($errores){
                    $_SESSION['error']=$errores[0];
                    header('Location:'.BASE_URL);
                }else{
                    $_SESSION['exito']='Categoria editada correctamente';
                    header('Location:'.BASE_URL);
                }
            }
        }
    }

    /**
     * Elimina una categoria por su id de la base de datos y redirige a la pagina principal
     * @return void Redirige a la pagina principal
     */
    public function eliminarCategoriaPorId(): void{
        $error=$this->CategoriaService->borrarCategoriaPorId($_GET['idCategoria']);
        if(!$error){
            $_SESSION['exito']='Categoria eliminada correctamente';
            header('Location:'.BASE_URL);
        }else{
            $_SESSION['error']='No se ha podido eliminar la categoria';
            header('Location:'.BASE_URL);
        }

    }

    /**
     * Añade una categoria a la base de datos si no se ha enviado el formulario, si se ha enviado lo sanea y valida
     * y lo añade a la base de datos y redirige a la pagina principal
     * @return void Redirige a la pagina principal
     */
    public function addCategoria(): void{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['identificado'])){
            header('Location:'.BASE_URL);
            exit();
        }elseif ($_SESSION['identificado']->rol!='admin'){
            $_SESSION['error']='Solo los administradores pueden añadir categorias';
            header('Location:'.BASE_URL);
            exit();
        }
        if (isset($_POST['nombre'])){
            $nombre=$this->Categoria->SaneaYValidaCategoria($_POST['nombre']);
            if ($nombre!=false){
                $resultado=$this->CategoriaService->addCategoria($nombre);
                if($resultado==null){
                    $_SESSION['exito']='Categoria añadida correctamente';
                    header('Location:'.BASE_URL);
                }else{
                    $_SESSION['error']='No se ha podido añadir la categoria';
                    header('Location:'.BASE_URL);
                }
            }else{
                $_SESSION['error']='No se ha podido añadir la categoria';
                header('Location:'.BASE_URL);
            }
        }else{
            $this->pages->render('Categorias/GestionCategoria',['add'=>true]);
        }
    }
}

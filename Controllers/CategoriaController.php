<?php
namespace Controllers;
use Lib\Pages,
    Models\Categoria;

use Utils\Utils;

class CategoriaController{
    public function __construct()
    {
        $this->pages=new Pages();
        $this->Categoria=new Categoria();
    }

    public function obtenerCategorias(){
        $categoriasArray=$this->Categoria->getAll();
        return Categoria::fromArray($categoriasArray);

    }


    public function mostrarCategorias(){

        $this->pages->render('Categorias/CategoriasView',['categorias'=>$this->obtenerCategorias()]);
    }
}

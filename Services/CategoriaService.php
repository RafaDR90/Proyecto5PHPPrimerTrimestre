<?php
namespace Services;
use Repositorys\CategoriaRepository;

class CategoriaService{
    private CategoriaRepository $categoriaRepository;
    public function __construct() {
        $this->categoriaRepository = new CategoriaRepository();
    }

    public function addCategoria($nombre){
        return $this->categoriaRepository->addCategoria($nombre);
    }
    public function borrarCategoriaPorId(int $id):?string{
        return $this->categoriaRepository->borrarCategoriaPorId($id);
    }

    public function update($datos){
        return $this->categoriaRepository->update($datos);
    }
    public function obtenerCategoriaPorID($id){
        return $this->categoriaRepository->obtenerCategoriaPorID($id);
    }
    public function getAll(){
        return $this->categoriaRepository->getAll();
    }
}
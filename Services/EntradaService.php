<?php
namespace Services;
use Repositorys\EntradaRepository;

class EntradaService{
    private EntradaRepository $entradaRepository;
    public function __construct() {
        $this->entradaRepository = new EntradaRepository();
    }

    public function addEntrada($datos){
        return $this->entradaRepository->addEntrada($datos);
    }
    public function EntradaConId($id){
        return $this->entradaRepository->entradaConId($id);
    }

    public function editEntrada($entrada){
        return $this->entradaRepository->editEntrada($entrada);
    }

    public function deleteEntrada($id){
        return $this->entradaRepository->deleteEntrada($id);
    }

    public function getAllById($id){
        return $this->entradaRepository->getAllById($id);
    }
}
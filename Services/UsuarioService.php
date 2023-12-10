<?php
namespace Services;
use Repositorys\UsuarioRepository;
class UsuarioService{
    private UsuarioRepository $usuarioRepository;

    public function __construct() {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function create($usuario){
        return $this->usuarioRepository->create($usuario);
    }

    public function buscaMail($email){
        return $this->usuarioRepository->buscaMail($email);
    }
    public function getAllNameAndId(){
        return $this->usuarioRepository->getAllNameAndId();
    }
    public function editarPassword($id,$password){
        return $this->usuarioRepository->editarPassword($id,$password);
    }

    public function editarDato($id,$dato,$valor){
        return $this->usuarioRepository->editarDato($id,$dato,$valor);
    }

    public function editarDatos($id,array $datos){
        return $this->usuarioRepository->editarDatos($id,$datos);
    }



}
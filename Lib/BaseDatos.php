<?php

namespace Lib;
use PDO;
class BaseDatos {
    private  $conexion;
    private mixed $resultado;
    private string $servidor ;
    private string $usuario ;
    private string $pass ;
    private string $base_datos;
    function __construct()
    {
        $this->servidor = $_ENV['DB_HOST'];
        $this->usuario = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->pass = $_ENV['DB_PASS'];
        $this->base_datos = $_ENV['DB_DATABASE'];
        $this->conexion=$this->conectar();
    }
    function conectar() : PDO {
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );
            $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}",$this->usuario,$this->pass,$opciones);
            return $conexion;
        } catch (PDOException $e) {
            echo "Ha surgido un error y no se puede conectar con la base de datos".$e->getMessage();
            exit;
        }
    }
    public function consulta(string $consultaSQL): void{
        $this->resultado=$this->conexion->query($consultaSQL);
    }
    public function extraer_todos(): mixed{
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);

    }
    public function extraer_registro(): mixed{
        return ($fila=$this->resultado->fetch(PDO::FETCH_ASSOC))? $fila:false;
    }
    public function prepara(string $querySQL) {
        return $this->conexion->prepare($querySQL);
    }
    public function filasAfectadas(): int{
        return $this->resultado->rowCount();
    }

    public function cierraConexion(){
        $this->conexion=null;
    }
}
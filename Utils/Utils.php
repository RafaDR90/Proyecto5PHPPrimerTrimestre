<?php
namespace Utils;
class Utils{
    /**
     * Borra una sesion
     * @param $nombreSession string Nombre de la sesion a borrar
     * @return void No devuelve nada
     */
    public static function deleteSession(string $nombreSession):void{
        if (isset($_SESSION[$nombreSession])){
            $_SESSION[$nombreSession]=null;
            unset($_SESSION[$nombreSession]);
        }
    }

}

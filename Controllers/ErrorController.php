<?php

namespace Controllers;

class ErrorController{
    /**
     * Muestra el error La página que buscas no existe en caso de que no se encuentre la pagina.
     * @return string
     */
    public static function show_error404():string{
        return "<p>La página que buscas no existe </p>";
    }
}
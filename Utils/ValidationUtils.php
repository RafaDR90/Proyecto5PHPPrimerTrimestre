<?php
namespace Utils;

class ValidationUtils{
    /**
     * Sanea y Valida numero y lo devuelve en Integer
     * @param $input
     * @return int|null
     */
    public static function SVNumero($input) {
        $cleanedInput = htmlspecialchars(trim($input), ENT_QUOTES, 'utf-8');
        if (ctype_digit($cleanedInput)) {
            $validatedInteger = (int)$cleanedInput;
            return $validatedInteger;
        } else {
            return null;
        }
    }
}
<?php

namespace App\Utils;

class GeneradorCodigos
{
    /**
     * Generate a random code of a given length.
     *
     * @param int $len The length of the code to generate.
     * @return string The generated random code.
     */
    public static function generateRandomCode($len = 10)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        $alphabet = $uppercase . $lowercase . $numbers;
        
        $code = '';
        $alphaLength = strlen($alphabet) - 1;
        
        for ($i = 0; $i < $len; $i++) {
            $n = rand(0, $alphaLength);
            $code .= $alphabet[$n];
        }
        
        return $code;
    }
}
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Jwt extends Facade
{
    protected static function getFacadeAccessor()
    {
        // Debe coincidir con el alias definido en el Service Provider
        return 'jwt';
    }
}

<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;

        protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'contrasena',
        'id_rol',
        'fecha_registro',
        'ultimo_acceso'
    ];

    
    protected $hidden = ['contrasena'];

    /**
     * Relación con el catálogo de roles.
     */
    public function rol()
    {
        return $this->belongsTo(CatRol::class, 'id_rol', 'id');
    }

    /**
     * Lumen/Eloquent usa por defecto 'password'. Indicamos que es 'contrasena'.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}

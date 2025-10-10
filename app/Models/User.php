<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'vw_usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'contrasena',
        'id_rol',
        'rol',
        'fecha_registro',
        'ultimo_acceso'
    ];


    
        public function rol()
    {
        return $this->belongsTo(CatRol::class, 'id_rol', 'id');
    }

    public function coordinador()
    {
        return $this->hasOne(Coordinador::class, 'id_usuario', 'id');
    }

    


    protected $hidden = ['contrasena'];

  
    /**
     * Lumen/Eloquent usa por defecto 'password'. Indicamos que es 'contrasena'.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Crea un nuevo usuario utilizando el procedimiento almacenado sp_usuario_crear.
     *
     * @param string $p_nombre
     * @param string $p_apellido_paterno
     * @param string $p_apellido_materno
     * @param string $p_correo
     * @param string $p_contrasena_hash
     * @param string $p_rol
     * @return bool
     */
    public static function crearUsuario(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, string $p_rol)
    {
        return DB::statement(
            'CALL sp_usuario_crear(?, ?, ?, ?, ?, ?)',
            [
                $p_nombre,
                $p_apellido_paterno,
                $p_apellido_materno,
                $p_correo,
                $p_contrasena_hash,
                $p_rol
            ]
        );
    }

}
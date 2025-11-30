<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class User
 *
 * @package App\Models
 *
 * This model represents a User in the system. It implements AuthenticatableContract and AuthorizableContract
 * for authentication and authorization functionalities, and uses HasFactory for model factories.
 * It primarily interacts with the 'vw_usuarios' view.
 *
 * @property int $id
 * @property string $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string $correo
 * @property string $contrasena
 * @property int $id_rol
 * @property string $rol
 * @property string $fecha_registro
 * @property string $ultimo_acceso
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'usuario';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Get the role associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rol()
    {
        return $this->belongsTo(CatRol::class, 'id_rol', 'id');
    }

    /**
     * Get the coordinator record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coordinador()
    {
        return $this->hasOne(Coordinador::class, 'usuario_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['contrasena'];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Creates a new user using the stored procedure `sp_usuario_crear`.
     *
     * @param string $p_nombre The first name of the user.
     * @param string $p_apellido_paterno The paternal last name of the user.
     * @param string $p_apellido_materno The maternal last name of the user.
     * @param string $p_correo The email address of the user.
     * @param string $p_contrasena_hash The hashed password for the user.
     * @param string $p_rol The role of the user.
     * @return bool True if the stored procedure executed successfully, false otherwise.
     */
    public static function crearUsuario(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, int $p_id_rol)
    {
        return self::create([
            'nombre' => $p_nombre,
            'apellido_paterno' => $p_apellido_paterno,
            'apellido_materno' => $p_apellido_materno,
            'correo' => $p_correo,
            'contrasena' => $p_contrasena_hash,
            'id_rol' => $p_id_rol,
            'fecha_registro' => now(),
            'ultimo_acceso' => now()
        ]);
    }

}
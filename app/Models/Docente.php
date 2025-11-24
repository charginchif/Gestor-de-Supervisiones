<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Docente
 *
 * @package App\Models
 *
 * This model represents a Docente (Teacher) in the system.
 * It interacts with the 'vw_docente_perfil' view to manage teacher profiles.
 *
 * @OA\Schema(
 *     title="Docente",
 *     description="Modelo de Docente",
 *     @OA\Xml(
 *         name="Docente"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_docente",
 *             title="ID del Docente",
 *             description="Identificador único del docente",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="grado_academico",
 *             title="Grado Académico",
 *             description="Grado académico del docente",
 *             type="string",
 *             example="Maestría",
 *             nullable=true,
 *             maxLength=100
 *         ),
 *         @OA\Property(
 *             property="id_usuario",
 *             title="ID de Usuario",
 *             description="Identificador único del usuario asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             title="Nombre",
 *             description="Nombre del docente",
 *             type="string",
 *             example="Ana",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="apellido_paterno",
 *             title="Apellido Paterno",
 *             description="Apellido paterno del docente",
 *             type="string",
 *             example="Gómez",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="apellido_materno",
 *             title="Apellido Materno",
 *             description="Apellido materno del docente",
 *             type="string",
 *             example="Martínez",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="correo",
 *             title="Correo Electrónico",
 *             description="Correo electrónico del docente",
 *             type="string",
 *             format="email",
 *             example="ana.gomez@example.com",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="fecha_registro",
 *             title="Fecha de Registro",
 *             description="Fecha de registro del docente",
 *             type="string",
 *             format="date-time"
 *         ),
 *         @OA\Property(
 *             property="ultimo_acceso",
 *             title="Último Acceso",
 *             description="Fecha del último acceso del docente",
 *             type="string",
 *             format="date-time"
 *         ),
 *         @OA\Property(
 *             property="id_rol",
 *             title="ID de Rol",
 *             description="Identificador único del rol del usuario",
 *             type="integer",
 *             example=2
 *         )
 *     }
 * )
 */
class Docente extends Model
{
    /**
     * The database table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_docente_perfil';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_docente';

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
        'id_docente',
        'grado_academico',
        'id_usuario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'fecha_registro',
        'ultimo_acceso',
        'id_rol'
    ];

    /**
     * Get the user associated with the Docente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    /**
     * Creates a new Docente and associated User in the database using a stored procedure.
     *
     * @param string $p_nombre The first name of the docente.
     * @param string $p_apellido_paterno The paternal last name of the docente.
     * @param string $p_apellido_materno The maternal last name of the docente.
     * @param string $p_correo The email address of the docente.
     * @param string $p_contrasena_hash The hashed password for the associated user.
     * @param string|null $p_grado_academico The academic degree of the docente (optional).
     * @return object An object containing the generated user ID and docente ID.
     */
    public static function crearDocente(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, ?string $p_grado_academico)
    {
        $sql = 'CALL sp_crear_docente(?, ?, ?, ?, ?, ?, @p_out_id_usuario, @p_out_id_docente)';
        DB::select($sql, [$p_nombre, $p_apellido_paterno, $p_apellido_materno, $p_correo, $p_contrasena_hash, $p_grado_academico]);
        $results = DB::select('SELECT @p_out_id_usuario as id_usuario, @p_out_id_docente as id_docente');
        return $results[0];
    }
}
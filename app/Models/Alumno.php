<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alumno extends Model 
{
    // Nombre de la vista
    protected $table = 'vw_alumno_perfil';

    // Clave primaria presente en la vista
    protected $primaryKey = 'id_alumno';

    // La vista no tiene increment (evita intentos de insert/update)
    public $incrementing = false;

    // La vista no maneja created_at / updated_at
    public $timestamps = false;

    // Campos asignables si llegas a mapear lecturas masivas (no se usan para insert/update)
    protected $fillable = [
        'id_alumno', 'matricula',
        'id_usuario', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo',
        'fecha_registro', 'ultimo_acceso',
        'id_carrera',
        'id_rol'
    ];

    // --- Relaciones útiles (lectura) ---
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // --- Accessors / Helpers ---
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public static function crearAlumno(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, string $p_matricula, int $p_id_carrera)
    {
        $sql = 'CALL sp_crear_alumno(?, ?, ?, ?, ?, ?, ?, @p_out_id_usuario, @p_out_id_alumno)';
        $success = DB::statement($sql, [$p_nombre, $p_apellido_paterno, $p_apellido_materno, $p_correo, $p_contrasena_hash, $p_matricula, $p_id_carrera]);

        if (!$success) {
            return null; 
        }

        $results = DB::select('SELECT @p_out_id_usuario as id_usuario, @p_out_id_alumno as id_alumno');

        if (empty($results) || !isset($results[0]->id_usuario)) {
            return null; // Stored procedure executed, but didn't return expected IDs
        }

        return $results[0];
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

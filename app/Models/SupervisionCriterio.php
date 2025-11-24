<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SupervisionCriterio
 *
 * @package App\Models
 *
 * This model represents a criterion for a supervision in the system.
 * It interacts with the 'supervision_criterios' table.
 */
class SupervisionCriterio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'supervision_criterios';

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
        'id_supervision',
        'id_supcriterio',
        'estado',
    ];
}

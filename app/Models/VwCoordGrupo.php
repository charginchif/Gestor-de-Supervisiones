<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwCoordGrupo
 *
 * @package App\Models
 *
 * This model represents a view for coordinator groups.
 * It provides a consolidated view of groups managed by a coordinator.
 */
class VwCoordGrupo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_coord_grupos';

    /**
     * The primary key associated with the table.
     * Since this is a view, it does not have a traditional primary key.
     *
     * @var null
     */
    protected $primaryKey = null;

    /**
     * Indicates if the model's ID is auto-incrementing.
     * Since this is a view without a primary key, it is set to false.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
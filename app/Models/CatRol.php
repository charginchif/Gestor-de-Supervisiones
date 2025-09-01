<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatRol extends Model
{
    protected $table = 'cat_rol';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}

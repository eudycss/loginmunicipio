<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adm_modulos extends Model
{
    use HasFactory;

    protected $fillable = [
        'mo_id',
        'mo_padre',
        'mo_nombre',
        'mo_descripcion',
        'mo_estado',
    ];
    protected $guarded = ['mo_id'];
    protected $primaryKey = 'mo_id';
    protected $table = 'ccc_modulo';
    protected $connection = '';
    public $timestamps = false;

    
}
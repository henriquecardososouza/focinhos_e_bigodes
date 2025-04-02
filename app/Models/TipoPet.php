<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPet extends Model
{
    protected $table = 'tipo_pets';
    protected $primaryKey = "nome";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        "nome"
    ];
}

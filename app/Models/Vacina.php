<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacina extends Model
{
    protected $primaryKey = "nome";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        "nome",
        "descricao"
    ];

    public function pet()
    {
        return $this->belongsToMany(Pet::class, 'pet_has_vacina', 'vacina', 'pet', 'nome', 'codigo');
    }
}

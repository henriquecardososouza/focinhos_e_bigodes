<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $primaryKey = "codigo";
    public $timestamps = false;

    protected $fillable = [
        "codigo",
        "nome",
        "data_nasc",
        "raca",
    ];

    public function dadosRaca()
    {
        return $this->belongsTo(Raca::class, 'raca', 'nome');
    }

    public function cliente()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_has_pet', 'pet', 'cliente', 'codigo', 'email');
    }

    public function vacinas()
    {
        return $this->belongsToMany(Vacina::class, 'pet_has_vacina', 'pet', 'vacina', 'codigo', 'nome');
    }
}

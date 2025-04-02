<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    protected $primaryKey = "nome";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        "nome",
        "tipo"
    ];

    public function dadosTipo()
    {
        return $this->belongsTo(TipoPet::class, 'tipo', 'nome');
    }
}

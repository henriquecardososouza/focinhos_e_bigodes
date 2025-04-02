<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    public $primaryKey = "tipo";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "tipo",
        "descricao",
        "valor"
    ];

    public function getValorAttribute()
    {
        return "R$ " . number_format($this->attributes["valor"] / 100, 2, ',', '.');
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_contrata_servico', 'servico', 'cliente', 'tipo', 'email');
    }
}

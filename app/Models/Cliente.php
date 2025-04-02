<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "email",
        "nome",
        "telefone",
        "endereco"
    ];

    public function dadosEndereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco', 'id');
    }

    public function pet()
    {
        return $this->belongsToMany(Pet::class, 'cliente_has_pet', 'cliente', 'pet', 'email', 'codigo');
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'cliente_contrata_servico', 'cliente', 'servico', 'email', 'tipo');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente', 'email');
    }
}

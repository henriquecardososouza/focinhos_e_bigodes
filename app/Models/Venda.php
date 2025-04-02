<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    public $primaryKey = 'codigo';
    public $timestamps = false;

    protected $fillable = [
        'cliente',
        'funcionario',
        'data'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'venda_has_produtos', 'venda', 'produto', 'codigo', 'codigo');
    }

    public function dadosCliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente', 'email');
    }

    public function dadosFuncionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario', 'cpf');
    }
}

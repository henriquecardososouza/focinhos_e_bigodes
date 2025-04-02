<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $primaryKey = "endereco";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "endereco",
        "gerente"
    ];

    public function dadosEndereco() {
        return $this->belongsTo(Endereco::class, 'endereco', 'id');
    }

    public function dadosGerente() {
        return $this->belongsTo(Funcionario::class, 'gerente', 'cpf');
    }
}

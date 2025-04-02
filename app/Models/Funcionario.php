<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $primaryKey = "cpf";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        "cpf",
        "nome",
        "telefone",
        "cargo",
        "unidade",
        "credencial"
    ];

    public function credencial()
    {
        return $this->belongsTo(Credencial::class, "credencial", "email");
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade', 'endereco');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo', 'nome');
    }

    public function servicosMotorista()
    {
        return $this->belongsToMany(Servico::class, 'cliente_contrata_servico', 'motorista', 'cliente', 'cpf', 'tipo');
    }

    public function servicosAtendidos()
    {
        return $this->belongsToMany(Servico::class, 'cliente_contrata_servico', 'atendente', 'servico', 'cpf', 'tipo');
    }

    public function servicosPrestados()
    {
        return $this->belongsToMany(Servico::class, 'cliente_contrata_servico', 'funcionario', 'servico', 'cpf', 'tipo');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'funcionario', 'cpf');
    }
}

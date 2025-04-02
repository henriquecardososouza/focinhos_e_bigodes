<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $primaryKey = "codigo";
    public $timestamps = false;

    protected $fillable = [
        "codigo",
        "nome",
        "descricao",
        "valor",
        "estoque"
    ];

    public function getValorAttribute()
    {
        return "R$ " . number_format($this->attributes["valor"] / 100, 2, ',', '.');
    }

    public function vendas()
    {
        return $this->belongsToMany(Venda::class, 'venda_has_produtos', 'produto', 'venda', 'codigo', 'codigo');
    }
}

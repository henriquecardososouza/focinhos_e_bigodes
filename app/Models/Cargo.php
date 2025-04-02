<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $primaryKey = "nome";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        "nome",
        "salario"
    ];

    public function getSalarioAttribute()
    {
        return "R$ " . number_format($this->attributes["salario"] / 100, 2, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    public $timestamps = false;
    protected $fillable = [
        "rua",
        "numero",
        "bairro",
        "cidade",
    ];
}

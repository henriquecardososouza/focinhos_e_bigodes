<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CreateFuncionarios::class);
        $this->call(CreateServicos::class);
        $this->call(CreateTiposPet::class);
        $this->call(CreateVacinas::class);
    }
}

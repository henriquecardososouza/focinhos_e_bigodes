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
        //$this->call(CreateUnidades::class);
        //$this->call(CreateClientes::class);
        //$this->call(CreatePets::class);
        //$this->call(CreateVacinas::class);
        $this->call(CreateServicos::class);
    }
}

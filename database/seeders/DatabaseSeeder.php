<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Libro;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'nombre' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin')
        ]);
        Libro::factory(100)->create();
        User::factory(10)->hasLibros(2)->create();


    }
}

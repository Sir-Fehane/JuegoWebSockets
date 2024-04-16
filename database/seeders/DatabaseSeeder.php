<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['rol' => 'invitado'],
            ['rol' => 'usuario'],
            ['rol' => 'administrador'],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'administrador2',
                'email' => '22170011@uttcampus.edu.mx',
                'password' => Hash::make('papitaman'), // Encriptar la contraseña
                'rol' => 3,
                'is_active' => 1
            ]
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name'     => 'Administrador',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('roles')->insert([
            'name'     => 'Operador',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('roles')->insert([
            'name'     => 'Escola',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('roles')->insert([
            'name'     => 'Estudante',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

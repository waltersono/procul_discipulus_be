<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Operador da Escola
        DB::table('permissions')->insert([
            'id'  => 1,
            'name'     => 'Visualizar estudantes',
        ]);
        DB::table('permissions')->insert([
            'id'  => 2,
            'name'     => 'Visualizar notas',
        ]);
        DB::table('permissions')->insert([
            'id'  => 3,
            'name'     => 'Gerar código de estudante',
        ]);
        

        // Operador
        DB::table('permissions')->insert([
            'id'  => 4,
            'name'     => 'Gerir Perfil',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 5,
            'name'     => 'Reniciar senha',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 6,
            'name'     => 'Gerir Escolas',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 7,
            'name'     => 'Gerir Cursos',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 8,
            'name'     => 'Gerir Disciplinas',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 9,
            'name'     => 'Gerir Estudantes',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 10,
            'name'     => 'Gerir turmas',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 11,
            'name'     => 'Alocar Disciplinas a cursos',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 12,
            'name'     => 'Gerir conteudo das disciplinas',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 13,
            'name'     => 'Gerir Avaliações',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'id'  => 14,
            'name'     => 'Visualizar resultados',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

    }
}

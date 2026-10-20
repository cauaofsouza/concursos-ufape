<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $grupos = [
            ['Formação Acadêmica', 'Titulação e cursos de pós-graduação', 3],
            ['Experiência Didática', 'Atuação docente comprovada', 2],
            ['Produção Científica', 'Artigos, livros e patentes', 3],
            ['Experiência Profissional', 'Atuação profissional fora da docência', 2],
        ];

        $rows = [];
        foreach ($grupos as $g) {
            $rows[] = [
                'nome'       => $g[0],
                'descricao'  => $g[1],
                'peso'       => $g[2],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('grupos')->insert($rows);
    }
}

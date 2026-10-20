<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpcoesVagasSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $areas = [
            'Algoritmos e Estruturas de Dados',
            'Engenharia de Software',
            'Banco de Dados',
            'Redes de Computadores',
            'Inteligência Artificial',
            'Matemática Discreta',
        ];

        $rows = [];
        foreach (DB::table('concursos')->orderBy('id')->pluck('id') as $i => $concursoId) {
            for ($j = 0; $j < 3; $j++) {
                $rows[] = [
                    'nome'        => $areas[($i + $j) % count($areas)],
                    'concursos_id' => $concursoId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('opcoes_vagas')->insert($rows);
    }
}

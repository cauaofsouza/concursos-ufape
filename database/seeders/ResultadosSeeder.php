<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultadosSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $concursos = DB::table('concursos')
            ->where('data_resultado_selecao', '<=', Carbon::today()->toDateString())
            ->pluck('id');

        $rows = [];
        foreach ($concursos as $concursoId) {
            foreach (['Resultado Preliminar' => 'preliminar', 'Resultado Final' => 'final'] as $nome => $slug) {
                $rows[] = [
                    'nome'        => $nome,
                    'arquivo'     => "resultados/{$concursoId}/resultado_{$slug}.pdf",
                    'concurso_id' => $concursoId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        if ($rows) {
            DB::table('resultados')->insert($rows);
        }
    }
}

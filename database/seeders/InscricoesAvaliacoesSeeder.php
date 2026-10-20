<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InscricoesAvaliacoesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();
        $hoje = Carbon::today()->toDateString();

        $grupos = DB::table('grupos')->get();
        $pesoTotal = $grupos->sum('peso');

        $avaliacoes = DB::table('avaliacoes')
            ->join('inscricoes', 'inscricoes.id', '=', 'avaliacoes.inscricoes_id')
            ->join('concursos', 'concursos.id', '=', 'inscricoes.concursos_id')
            ->select('avaliacoes.id', 'avaliacoes.inscricoes_id', 'concursos.data_resultado_selecao')
            ->get();

        foreach ($avaliacoes as $avaliacao) {
            $rows = [];
            $soma = 0;

            foreach ($grupos as $grupo) {
                $nota = $faker->numberBetween(4, 10);
                $soma += $nota * $grupo->peso;

                $rows[] = [
                    'avaliacoes_id' => $avaliacao->id,
                    'grupo_id'      => $grupo->id,
                    'nota'          => $nota,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            DB::table('inscricoes_avaliacoes')->insert($rows);

            $media = round($soma / $pesoTotal, 2);
            DB::table('avaliacoes')->where('id', $avaliacao->id)->update(['nota' => $media, 'updated_at' => $now]);

            // Concursos com resultado já divulgado: define aprovado/reprovado
            if ($avaliacao->data_resultado_selecao <= $hoje) {
                DB::table('inscricoes')->where('id', $avaliacao->inscricoes_id)->update([
                    'status'     => $media >= 7 ? InscricoesSeeder::STATUS_APROVADO : InscricoesSeeder::STATUS_REPROVADO,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}

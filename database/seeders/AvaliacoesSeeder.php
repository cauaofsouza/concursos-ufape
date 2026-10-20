<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliacoesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $hoje = Carbon::today()->toDateString();

        // Inscrições em análise cujo prazo de envio de documentos já encerrou
        $ids = DB::table('inscricoes')
            ->join('concursos', 'concursos.id', '=', 'inscricoes.concursos_id')
            ->where('inscricoes.status', InscricoesSeeder::STATUS_EM_ANALISE)
            ->where('concursos.data_fim_envio_doc', '<', $hoje)
            ->pluck('inscricoes.id');

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = [
                'nota'            => 0, // recalculada em InscricoesAvaliacoesSeeder
                'inscricoes_id'   => $id,
                'ficha_avaliacao' => "fichas/ficha_avaliacao_{$id}.pdf",
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        DB::table('avaliacoes')->insert($rows);
    }
}

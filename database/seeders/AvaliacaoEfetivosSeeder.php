<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliacaoEfetivosSeeder extends Seeder
{
    /**
     * STATUS_ENUM: deferido = 0, indeferido = 1.
     * Gera registros apenas para concursos efetivos (tipo = 1) com resultado já definido.
     * ATENÇÃO: mapeamento aprovado -> deferido e reprovado -> indeferido é suposição.
     */
    public function run()
    {
        $now = Carbon::now();

        $inscricoes = DB::table('inscricoes')
            ->join('concursos', 'concursos.id', '=', 'inscricoes.concursos_id')
            ->where('concursos.tipo', 1)
            ->whereIn('inscricoes.status', [InscricoesSeeder::STATUS_APROVADO, InscricoesSeeder::STATUS_REPROVADO])
            ->select('inscricoes.id', 'inscricoes.concursos_id', 'inscricoes.status')
            ->get();

        $rows = [];
        foreach ($inscricoes as $inscricao) {
            $rows[] = [
                'status'       => $inscricao->status === InscricoesSeeder::STATUS_APROVADO ? 0 : 1,
                'concurso_id'  => $inscricao->concursos_id,
                'inscricao_id' => $inscricao->id,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        if ($rows) {
            DB::table('avaliacao_efetivos')->insert($rows);
        }
    }
}

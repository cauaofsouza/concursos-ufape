<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcursosSeeder extends Seeder
{
    /**
     * Cenários (offset = dias entre hoje e o início das inscrições):
     *  A: inscrições ainda não abertas
     *  B: inscrições abertas
     *  C: envio de documentos encerrado, avaliação em andamento
     *  D: resultado já divulgado
     *
     * Campo "tipo": 0 = substituto, 1 = efetivo (TIPO_ENUM).
     */
    public function run()
    {
        $now = Carbon::now();
        $hoje = Carbon::today();

        $coordenadores = DB::table('users')->where('role', 'chefeSetorConcursos')->orderBy('id')->pluck('id')->all();

        $cenarios = [
            ['titulo' => 'Seleção Professor Substituto - Computação (Em breve)', 'tipo' => 0, 'vagas' => 2, 'offset' => 15],
            ['titulo' => 'Concurso Professor Efetivo - Engenharia de Software (Inscrições abertas)', 'tipo' => 1, 'vagas' => 3, 'offset' => -5],
            ['titulo' => 'Seleção Professor Substituto - Matemática (Em avaliação)', 'tipo' => 0, 'vagas' => 2, 'offset' => -35],
            ['titulo' => 'Concurso Professor Efetivo - Redes (Resultado divulgado)', 'tipo' => 1, 'vagas' => 4, 'offset' => -60],
        ];

        $rows = [];
        foreach ($cenarios as $i => $c) {
            $inicio = $hoje->copy()->addDays($c['offset']);

            $rows[] = [
                'titulo'                       => $c['titulo'],
                'qtd_vagas'                    => $c['vagas'],
                'descricao'                    => 'Processo seletivo para preenchimento de vagas conforme edital. Seed de teste.',
                'data_inicio_inscricao'        => $inicio->toDateString(),
                'data_fim_inscricao'           => $inicio->copy()->addDays(20)->toDateString(),
                'data_fim_isencao_inscricao'   => $inicio->copy()->addDays(5)->toDateString(),
                'data_fim_pagamento_inscricao' => $inicio->copy()->addDays(23)->toDateString(),
                'data_inicio_envio_doc'        => $inicio->copy()->addDays(21)->toDateString(),
                'data_fim_envio_doc'           => $inicio->copy()->addDays(28)->toDateString(),
                'data_resultado_selecao'       => $inicio->copy()->addDays(40)->toDateString(),
                'edital_geral'                 => 'editais/edital_geral_' . ($i + 1) . '.pdf',
                'edital_especifico'            => 'editais/edital_especifico_' . ($i + 1) . '.pdf',
                'declaracao_veracidade'        => 'declaracoes/declaracao_veracidade_' . ($i + 1) . '.pdf',
                'users_id'                     => $coordenadores[$i % count($coordenadores)],
                'tipo'                         => $c['tipo'],
                'created_at'                   => $now,
                'updated_at'                   => $now,
            ];
        }

        DB::table('concursos')->insert($rows);
    }
}

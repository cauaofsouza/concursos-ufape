<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArquivosSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $rows = [];
        foreach (DB::table('inscricoes')->pluck('id') as $id) {
            $dir = "arquivos/{$id}";
            $rows[] = [
                'inscricoes_id'            => $id,
                'dados_pessoais'           => "{$dir}/dados_pessoais.pdf",
                'curriculum_vitae_lattes'  => "{$dir}/curriculo_lattes.pdf",
                'formacao_academica'       => "{$dir}/formacao_academica.pdf",
                'experiencia_didatica'     => "{$dir}/experiencia_didatica.pdf",
                'producao_cientifica'      => "{$dir}/producao_cientifica.pdf",
                'experiencia_profissional' => "{$dir}/experiencia_profissional.pdf",
                'avaliacao_perfil'         => "{$dir}/avaliacao_perfil.pdf",
                'created_at'               => $now,
                'updated_at'               => $now,
            ];
        }

        DB::table('arquivos')->insert($rows);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentoExtrasSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $docs = [
            'Anexo I - Modelo de Currículo' => 'modelo_curriculo.pdf',
            'Anexo II - Formulário de Isenção' => 'formulario_isencao.pdf',
        ];

        $rows = [];
        foreach (DB::table('concursos')->pluck('id') as $concursoId) {
            foreach ($docs as $nome => $arquivo) {
                $rows[] = [
                    'nome'        => $nome,
                    'arquivo'     => "documentos_extras/{$concursoId}/{$arquivo}",
                    'concurso_id' => $concursoId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('documento_extras')->insert($rows);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotaDeTextosSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();

        $cores = ['primary', 'success', 'warning'];
        $titulos = ['Aviso importante', 'Como enviar os documentos', 'Critérios de desempate'];

        $rows = [];
        foreach (DB::table('concursos')->pluck('id') as $concursoId) {
            foreach ($titulos as $k => $titulo) {
                $rows[] = [
                    'titulo'       => $titulo,
                    'texto'        => $faker->paragraph(3),
                    'tipo'         => $k + 1,
                    'cor'          => $cores[$k],
                    'anexo'        => $k === 1 ? 'anexos/instrucoes_envio.pdf' : null,
                    'concursos_id' => $concursoId,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        DB::table('nota_de_textos')->insert($rows);
    }
}

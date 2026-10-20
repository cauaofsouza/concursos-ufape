<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InscricoesSeeder extends Seeder
{
    const STATUS_AGUARDANDO_PAGAMENTO = 'Aguardando pagamento';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_REPROVADO = 'reprovado';

    // ATENÇÃO: único status inventado. Troque pelo valor real do seu app.
    const STATUS_EM_ANALISE = 'Em análise';

    const TITULACAO_ENUM = ['graduacao', 'especializacao', 'mestrado', 'doutorado'];

    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();
        $hoje = Carbon::today()->toDateString();

        $temTitulacao = Schema::hasColumn('inscricoes', 'titulacao');

        $candidatos = DB::table('users')
            ->where('role', 'candidato')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $concursos = DB::table('concursos')
            ->where('data_inicio_inscricao', '<=', $hoje)
            ->orderBy('id')
            ->get();

        $vagasPorConcurso = DB::table('opcoes_vagas')->get()->groupBy('concursos_id');

        $rows = [];
        foreach ($candidatos as $userId) {
            $qtd = $faker->numberBetween(1, min(2, $concursos->count()));
            $escolhidos = $faker->randomElements($concursos->all(), $qtd);

            foreach ($escolhidos as $concurso) {
                $vagas = $vagasPorConcurso[$concurso->id]->pluck('id')->all();

                $row = [
                    'status'            => $concurso->data_fim_inscricao >= $hoje
                        ? self::STATUS_AGUARDANDO_PAGAMENTO
                        : self::STATUS_EM_ANALISE,
                    'solicitou_isencao' => $faker->boolean(20),
                    'cotista'           => $faker->boolean(25),
                    'pcd'               => $faker->boolean(10),
                    'users_id'          => $userId,
                    'concursos_id'      => $concurso->id,
                    'vagas_id'          => $faker->randomElement($vagas),
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                if ($temTitulacao) {
                    $row['titulacao'] = $faker->randomElement(self::TITULACAO_ENUM);
                }

                $rows[] = $row;
            }
        }

        DB::table('inscricoes')->insert($rows);
    }
}

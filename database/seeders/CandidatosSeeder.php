<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatosSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();

        $userIds = DB::table('users')
            ->where('role', 'candidato')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->pluck('id');

        $rows = [];
        foreach ($userIds as $i => $userId) {
            $estrangeiro = ($i % 10 === 9); // 10% estrangeiros

            $rows[] = [
                'cpf'                        => $faker->cpf(false),
                'documento_de_identificacao' => $faker->numerify('#######'),
                'orgao_emissor'              => $estrangeiro ? 'DPF' : 'SSP/' . $faker->stateAbbr,
                'pais_origem'                => $estrangeiro ? $faker->randomElement(['Argentina', 'Portugal', 'Colômbia']) : 'Brasil',
                'data_de_nascimento'         => $faker->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
                'nome_do_pai'                => $faker->name('male'),
                'nome_da_mae'                => $faker->name('female'),
                'estrangeiro'                => $estrangeiro,
                'celular'                    => $faker->numerify('(##) 9####-####'),
                'telefone'                   => $faker->numerify('(##) ####-####'),
                'users_id'                   => $userId,
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ];
        }

        DB::table('candidatos')->insert($rows);
    }
}

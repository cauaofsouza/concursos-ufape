<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnderecosSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();

        $bairros = ['Centro', 'Boa Vista', 'Jardim América', 'Heliópolis', 'Santo Antônio', 'Aluísio Pinto'];

        $userIds = DB::table('users')
            ->where('role', 'candidato')
            ->whereNull('deleted_at')
            ->pluck('id');

        $rows = [];
        foreach ($userIds as $userId) {
            $rows[] = [
                'cep'         => $faker->numerify('########'),
                'logradouro'  => $faker->streetName,
                'numero'      => (string) $faker->numberBetween(1, 2000),
                'bairro'      => $faker->randomElement($bairros),
                'cidade'      => $faker->city,
                'uf'          => $faker->stateAbbr,
                'complemento' => $faker->boolean(30) ? 'Apto ' . $faker->numberBetween(101, 804) : null,
                'users_id'    => $userId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('enderecos')->insert($rows);
    }
}

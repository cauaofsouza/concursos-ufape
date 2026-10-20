<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $faker->seed(1234);
        $now = Carbon::now();
        $password = Hash::make('password');

        $make = function ($nome, $sobrenome, $email, $role, $deletedAt = null) use ($now, $password) {
            return [
                'nome'                      => $nome,
                'sobrenome'                 => $sobrenome,
                'email'                     => $email,
                'email_verified_at'         => $now,
                'password'                  => $password,
                'remember_token'            => Str::random(10),
                'role'                      => $role,
                'deleted_at'                => $deletedAt,
                'current_team_id'           => null,
                'profile_photo_path'        => null,
                'two_factor_secret'         => null,
                'two_factor_recovery_codes' => null,
                'created_at'                => $now,
                'updated_at'                => $now,
            ];
        };

        $rows = [
            $make('Admin', 'Sistema', 'admin@teste.com', 'admin'),
            $make('Carla', 'Coordenadora', 'coordenador1@teste.com', 'chefeSetorConcursos'),
            $make('Marcos', 'Coordenador', 'coordenador2@teste.com', 'chefeSetorConcursos'),
            $make('Ana', 'Avaliadora', 'avaliador1@teste.com', 'presidenteBancaExaminadora'),
            $make('Bruno', 'Avaliador', 'avaliador2@teste.com', 'presidenteBancaExaminadora'),
            $make('Clara', 'Avaliadora', 'avaliador3@teste.com', 'presidenteBancaExaminadora'),
            $make('Diego', 'Avaliador', 'avaliador4@teste.com', 'presidenteBancaExaminadora'),
            $make('Elisa', 'Avaliadora', 'avaliador5@teste.com', 'presidenteBancaExaminadora'),
        ];

        for ($i = 1; $i <= 20; $i++) {
            $rows[] = $make($faker->firstName, $faker->lastName, "candidato{$i}@teste.com", 'candidato');
        }

        // Usuário com soft delete
        $rows[] = $make('Usuario', 'Excluido', 'excluido@teste.com', 'candidato', $now);

        DB::table('users')->insert($rows);
    }
}

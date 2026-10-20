<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChefeDaBancaSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $avaliadores = DB::table('users')->where('role', 'presidenteBancaExaminadora')->orderBy('id')->pluck('id')->all();
        $total = count($avaliadores);

        $rows = [];
        foreach (DB::table('concursos')->orderBy('id')->pluck('id') as $i => $concursoId) {
            $rows[] = [
                'concursos_id' => $concursoId,
                'users_id'     => $avaliadores[$i % $total],
                'chefe'        => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
            $rows[] = [
                'concursos_id' => $concursoId,
                'users_id'     => $avaliadores[($i + 1) % $total],
                'chefe'        => false,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('chefe_da_banca')->insert($rows);
    }
}

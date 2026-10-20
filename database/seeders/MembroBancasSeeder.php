<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembroBancasSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $avaliadores = DB::table('users')->where('role', 'presidenteBancaExaminadora')->orderBy('id')->pluck('id')->all();
        $total = count($avaliadores);

        $rows = [];
        foreach (DB::table('opcoes_vagas')->orderBy('id')->get() as $i => $vaga) {
            for ($j = 0; $j < 3; $j++) {
                $rows[] = [
                    'vaga_id'     => $vaga->id,
                    'user_id'     => $avaliadores[($i + $j) % $total],
                    'chefe'       => $j === 0,
                    'concurso_id' => $vaga->concursos_id,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('membro_bancas')->insert($rows);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->limparTabelas();

        $this->call([
            UsersSeeder::class,
            EnderecosSeeder::class,
            CandidatosSeeder::class,
            ConcursosSeeder::class,
            OpcoesVagasSeeder::class,
            GruposSeeder::class,
            NotaDeTextosSeeder::class,
            DocumentoExtrasSeeder::class,
            ChefeDaBancaSeeder::class,
            MembroBancasSeeder::class,
            InscricoesSeeder::class,
            ArquivosSeeder::class,
            AvaliacoesSeeder::class,
            InscricoesAvaliacoesSeeder::class,
            AvaliacaoEfetivosSeeder::class,
            ResultadosSeeder::class,
        ]);
    }

    private function limparTabelas()
    {
        $tabelas = [
            'avaliacao_efetivos', 'resultados', 'inscricoes_avaliacoes', 'avaliacoes',
            'arquivos', 'inscricoes', 'membro_bancas', 'chefe_da_banca', 'documento_extras',
            'nota_de_textos', 'grupos', 'opcoes_vagas', 'concursos', 'candidatos',
            'enderecos', 'users',
        ];

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('TRUNCATE TABLE ' . implode(', ', $tabelas) . ' RESTART IDENTITY CASCADE');
            return;
        }

        Schema::disableForeignKeyConstraints();
        foreach ($tabelas as $tabela) {
            DB::table($tabela)->truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}

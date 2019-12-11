<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SistemaSeeder::class,
            MoedaSeeder::class,
            PaisSeeder::class,
            EmpresaSeeder::class,
            EstadoSeeder::class,
            FuncaoSeeder::class,
            ModuloSeeder::class,
            FuncionalidadeSeeder::class,
            PermissaoSeeder::class,
            AcessoSeeder::class,
            SetorSeeder::class,
            MesaSeeder::class,
            ComandaSeeder::class,
            ClienteSeeder::class,
            PrestadorSeeder::class,
            CarteiraSeeder::class,
            CaixaSeeder::class,
            FormaSeeder::class,
            CartaoSeeder::class,
            UnidadeSeeder::class,
            ClassificacaoSeeder::class,
            ServicoSeeder::class,
            BancoSeeder::class,
            OrigemSeeder::class,
            RegimeSeeder::class,
            ImpostoSeeder::class,
            CozinhaSeeder::class,
            // OperacaoSeeder::class,
/*             HorarioSeeder::class,
            SessaoSeeder::class,
            MovimentacaoSeeder::class, */
            OperacaoSeeder::class,
            IntegracaoSeeder::class,
            MetricaSeeder::class,

            // Cardápio de amostra
            CategoriaSeeder::class,
            ProdutoSeeder::class,
            ComposicaoSeeder::class,
            GrupoSeeder::class,
            PropriedadeSeeder::class,
            PacoteSeeder::class,
            EstoqueSeeder::class,
        ]);
    }
}

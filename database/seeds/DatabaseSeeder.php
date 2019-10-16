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
            ClienteSeeder::class,
            ModuloSeeder::class,
            FuncionalidadeSeeder::class,
            PermissaoSeeder::class,
            FuncaoSeeder::class,
            SetorSeeder::class,
            MesaSeeder::class,
            ComandaSeeder::class,
            CategoriaSeeder::class,
            UnidadeSeeder::class,
            MoedaSeeder::class,
            ProdutoSeeder::class,
            PaisSeeder::class,
            CreditoSeeder::class,
            ClassificacaoSeeder::class,
            BancoSeeder::class,
            ChequeSeeder::class,
        ]);
    }
}

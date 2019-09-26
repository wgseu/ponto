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
            ModuloSeeder::class,
            FuncionalidadeSeeder::class,
            PermissaoSeeder::class,
            FuncaoSeeder::class,
            SetorSeeder::class,
            MesaSeeder::class,
            ComandaSeeder::class,
        ]);
    }
}

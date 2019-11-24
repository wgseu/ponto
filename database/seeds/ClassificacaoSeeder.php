<?php

use App\Models\Classificacao;
use Illuminate\Database\Seeder;

class ClassificacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Classificacao([
            'descricao' => __('messages.movement'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.payment'),
        ]))->save();
    }
}

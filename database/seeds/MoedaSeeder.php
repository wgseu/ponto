<?php

use App\Models\Moeda;
use Illuminate\Database\Seeder;

class MoedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Moeda([
            'nome' => __('messages.real'),
            'simbolo' => __('messages.symbol_real'),
            'codigo' => __('messages.code_real'),
            'divisao' => 100,
            'fracao' => __('messages.fraction_real'),
            'formato' => __('messages.format_real'),
            'conversao' => 1,
            'ativa' => true,
        ]))->save();
        (new Moeda([
            'nome' => __('messages.dollar'),
            'simbolo' => __('messages.symbol_dollar'),
            'codigo' => __('messages.code_dollar'),
            'divisao' => 100,
            'fracao' => __('messages.fraction_dollar'),
            'formato' => __('messages.format_dollar'),
            'conversao' => 1,
            'ativa' => false,
        ]))->save();
        (new Moeda([
            'nome' => __('messages.euro'),
            'simbolo' => __('messages.symbol_euro'),
            'codigo' => __('messages.code_euro'),
            'divisao' => 100,
            'fracao' => __('messages.fraction_euro'),
            'formato' => __('messages.format_euro'),
            'conversao' => 1,
            'ativa' => false,
        ]))->save();
        (new Moeda([
            'nome' => __('messages.metical'),
            'simbolo' => __('messages.symbol_metical'),
            'codigo' => __('messages.code_metical'),
            'divisao' => 100,
            'fracao' => __('messages.fraction_metical'),
            'formato' => __('messages.format_metical'),
            'conversao' => 1,
            'ativa' => false,
        ]))->save();
    }
}

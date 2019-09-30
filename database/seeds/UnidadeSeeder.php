<?php

use Illuminate\Database\Seeder;
use App\Models\Unidade;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Unidade([
            'nome' => __('messages.unity'),
            'descricao' => __('messages.unity'),
            'sigla' => 'UN'
        ]))->save();
        (new Unidade([
            'nome' => __('messages.liter'),
            'descricao' => __('messages.unity_liter'),
            'sigla' => 'L'
        ]))->save();
        (new Unidade([
            'nome' => __('messages.grass'),
            'descricao' => __('messages.unity_grass'),
            'sigla' => 'g'
        ]))->save();
        (new Unidade([
            'nome' => __('messages.Calorie'),
            'descricao' => __('messages.unity_calorie'),
            'sigla' => 'cal'
        ]))->save();
        (new Unidade([
            'nome' => __('messages.joule'),
            'descricao' => __('messages.unity_joule'),
            'sigla' => 'J'
        ]))->save();
    }
}

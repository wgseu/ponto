<?php

use App\Models\Metrica;
use Illuminate\Database\Seeder;

class MetricaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Metrica([
            'nome' => __('messages.delivery'),
            'descricao' => __('messages.metrica_description_delivery'),
            'tipo' => Metrica::TIPO_ENTREGA,
        ]))->save();
        (new Metrica([
            'nome' => __('messages.presentation'),
            'descricao' => __('messages.metrica_description_presentation'),
            'tipo' => Metrica::TIPO_APRESENTACAO,
        ]))->save();
        (new Metrica([
            'nome' => __('messages.attendance'),
            'descricao' => __('messages.metrica_description_attendance'),
            'tipo' => Metrica::TIPO_ATENDIMENTO,
        ]))->save();
        (new Metrica([
            'nome' => __('messages.production'),
            'descricao' => __('messages.metrica_description_production'),
            'tipo' => Metrica::TIPO_PRODUCAO,
        ]))->save();
    }
}

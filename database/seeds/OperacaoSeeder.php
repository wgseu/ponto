<?php

use App\Models\Operacao;
use Illuminate\Database\Seeder;

class OperacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Operacao([
            'codigo' => 5101,
            'descricao' => __('messages.operation_description_5101'),
            'detalhes' => __('messages.operation_detail_5101'),
        ]))->save();
        (new Operacao([
            'codigo' => 5102,
            'descricao' => __('messages.operation_description_5102'),
            'detalhes' => __('messages.operation_detail_5102'),
        ]))->save();
        (new Operacao([
            'codigo' => 5103,
            'descricao' => __('messages.operation_description_5103'),
            'detalhes' => __('messages.operation_detail_5103'),
        ]))->save();
        (new Operacao([
            'codigo' => 5104,
            'descricao' => __('messages.operation_description_5104'),
            'detalhes' => __('messages.operation_detail_5104'),
        ]))->save();
        (new Operacao([
            'codigo' => 5115,
            'descricao' => __('messages.operation_description_5115'),
            'detalhes' => __('messages.operation_detail_5115'),
        ]))->save();
        (new Operacao([
            'codigo' => 5401,
            'descricao' => __('messages.operation_description_5401'),
            'detalhes' => __('messages.operation_detail_5401'),
        ]))->save();
        (new Operacao([
            'codigo' => 5405,
            'descricao' => __('messages.operation_description_5405'),
            'detalhes' => __('messages.operation_detail_5405'),
        ]))->save();
    }
}

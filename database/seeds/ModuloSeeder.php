<?php

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        (new Modulo([
            'id' => 1,
            'nome' => __('messages.tables'),
            'descricao' => __('messages.tables_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 2,
            'nome' => __('messages.cards'),
            'descricao' => __('messages.cards_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 3,
            'nome' => __('messages.counter'),
            'descricao' => __('messages.counter_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 4,
            'nome' => __('messages.delivery'),
            'descricao' => __('messages.delivery_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 5,
            'nome' => __('messages.delivery_online'),
            'descricao' => __('messages.delivery_online_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 6,
            'nome' => __('messages.toten'),
            'descricao' => __('messages.toten_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 7,
            'nome' => __('messages.fiscal'),
            'descricao' => __('messages.fiscal_description'),
            'habilitado' => true,
        ]))->save();

        (new Modulo([
            'id' => 8,
            'nome' => __('messages.table_self_service'),
            'descricao' => __('messages.table_self_service_description'),
            'habilitado' => true,
        ]))->save();
    }
}

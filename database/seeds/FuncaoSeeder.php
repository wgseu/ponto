<?php

use App\Models\Funcao;
use Illuminate\Database\Seeder;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Funcao([
            'descricao' => __('messages.administrator'),
            'remuneracao' => 1600,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.waiter'),
            'remuneracao' => 998,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.cash_operator'),
            'remuneracao' => 1200,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.cooker'),
            'remuneracao' => 998,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.caretaker'),
            'remuneracao' => 998,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.deliveryman'),
            'remuneracao' => 998,
        ]))->save();

        (new Funcao([
            'descricao' => __('messages.stockist'),
            'remuneracao' => 998,
        ]))->save();
    }
}

<?php

use App\Models\Cozinha;
use Illuminate\Database\Seeder;

class CozinhaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Cozinha([
            'nome' => __('messages.pizzeria'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.restaurant'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.steak_house'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.ice_cream_shop'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.pub'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.snack_bar'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.acaiteria'),
        ]))->save();

        (new Cozinha([
            'nome' => __('messages.pastelaria'),
        ]))->save();
    }
}

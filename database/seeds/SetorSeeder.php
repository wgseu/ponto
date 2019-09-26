<?php

use App\Models\Setor;
use Illuminate\Database\Seeder;

class SetorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Setor([
            'nome' => __('messages.covered_hall'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.open_lounge'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.cashier'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.delivery'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.parking'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.kitchen'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.barbecue'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.pizza_oven'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.stock'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.pub'),
        ]))->save();
        (new Setor([
            'nome' => __('messages.toilets'),
        ]))->save();
    }
}

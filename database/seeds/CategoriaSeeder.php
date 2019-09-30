<?php

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Categoria([
            'descricao' => __('messages.pizza_pastas'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.meals'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.beer'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.soda'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.juices'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.Cigarettes'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.spirits'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.waters'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.snacks'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.portions'),
        ]))->save();
        (new Categoria([
            'descricao' => __('messages.tropical_drinks'),
        ]))->save();
    }
}

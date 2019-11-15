<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Unidade;
use Faker\Generator as Faker;

$factory->define(Produto::class, function (Faker $faker) {
    $categoria = factory(Categoria::class)->create();
    $unidade = factory(Unidade::class)->create();
    return [
        'codigo' => $faker->unique()->numberBetween(1, 99999999),
        'categoria_id' => $categoria->id,
        'unidade_id' => $unidade->id,
        'descricao' => $faker->unique()->name,
    ];
});

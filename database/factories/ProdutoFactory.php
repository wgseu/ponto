<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Unidade;
use Faker\Generator as Faker;

$factory->define(Produto::class, function (Faker $faker) {
    $categoria_id = factory(Categoria::class)->create();
    $unidade_id = factory(Unidade::class)->create();
    return [
        'codigo' => $faker->unique()->name,
        'categoria_id' => $categoria_id->id,
        'unidade_id' => $unidade_id->id,
        'descricao' => $faker->unique()->name,
    ];
});

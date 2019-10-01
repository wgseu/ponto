<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Produto::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numberBetween(1, 999),
        'categoria_id' => 1,
        'unidade_id' => 1,
        'descricao' => $faker->word,
        'custo_producao' => 0,
    ];
});

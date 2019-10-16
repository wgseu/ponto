<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Imposto;
use Faker\Generator as Faker;

$factory->define(Imposto::class, function (Faker $faker) {
    return [
        'grupo' => Imposto::GRUPO_ICMS,
        'simples' => false,
        'substituicao' => false,
        'codigo' => $faker->numberBetween(1, 70),
        'descricao' => $faker->unique()->word,
    ];
});

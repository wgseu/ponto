<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Imposto;
use Faker\Generator as Faker;

$factory->define(Imposto::class, function (Faker $faker) {
    return [
        'grupo' => Imposto::GRUPO_ICMS,
        'simples' => true,
        'substituicao' => false,
        'codigo' => $faker->numberBetween(1, 100),
        'descricao' => $faker->unique()->word,
    ];
});

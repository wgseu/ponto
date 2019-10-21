<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Moeda::class, function (Faker $faker) {
    $simbolo = $faker->word;
    return [
        'nome' => $faker->word,
        'simbolo' => $simbolo,
        'codigo' => $faker->unique()->word,
        'divisao' => 100,
        'formato' => "$simbolo :value",
    ];
});

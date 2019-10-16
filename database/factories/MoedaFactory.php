<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Moeda::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'simbolo' => $faker->name,
        'codigo' => $faker->unique()->name,
        'divisao' => $faker->numberBetween(1, 70),
        'formato' => $faker->name,
    ];
});

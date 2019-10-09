<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Regime;
use Faker\Generator as Faker;

$factory->define(Regime::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numberBetween(1, 70),
        'descricao' => $faker->name,
    ];
});

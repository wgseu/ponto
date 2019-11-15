<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Regime;
use Faker\Generator as Faker;

$factory->define(Regime::class, function (Faker $faker) {
    return [
        'codigo' => $faker->unique()->numberBetween(1, 888),
        'descricao' => $faker->name,
    ];
});

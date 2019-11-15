<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Banco;
use Faker\Generator as Faker;

$factory->define(Banco::class, function (Faker $faker) {
    return [
        'numero' => $faker->unique()->numberBetween(1, 10000) . '',
        'fantasia' => $faker->unique()->name,
        'razao_social' => $faker->unique()->name,
    ];
});

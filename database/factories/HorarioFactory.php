<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Horario;
use Faker\Generator as Faker;

$factory->define(Horario::class, function (Faker $faker) {
    return [
        'inicio' => $faker->numberBetween(1, 70),
        'fim' => $faker->numberBetween(1, 70),
    ];
});

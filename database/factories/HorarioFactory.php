<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Horario;
use Faker\Generator as Faker;

$factory->define(Horario::class, function (Faker $faker) {
    $inicio = $faker->unique()->numberBetween(Horario::MINUTES_PER_DAY / 10, Horario::MINUTES_PER_DAY * 8 / 10) * 10;
    $fim = $inicio + 5;
    return [
        'inicio' => $inicio,
        'fim' => $fim,
    ];
});

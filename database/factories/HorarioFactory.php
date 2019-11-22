<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Horario;
use Faker\Generator as Faker;

$factory->define(Horario::class, function (Faker $faker) {
    $fim = $faker->unique()->numberBetween(Horario::MINUTES_PER_DAY + 200, Horario::MINUTES_PER_DAY * 8);
    $inicio = $faker->unique()->numberBetween(Horario::MINUTES_PER_DAY + 100, $fim);
    return [
        'inicio' => $inicio,
        'fim' => $fim,
    ];
});

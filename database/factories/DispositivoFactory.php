<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dispositivo;
use Faker\Generator as Faker;

$factory->define(Dispositivo::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'serial' => $faker->unique()->name,
    ];
});

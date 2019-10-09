<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comanda;
use Faker\Generator as Faker;

$factory->define(Comanda::class, function (Faker $faker) {
    return [
        'numero' => $faker->numberBetween(1, 70),
        'nome' => $faker->unique()->name,
    ];
});

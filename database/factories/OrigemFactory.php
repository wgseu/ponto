<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Origem;
use Faker\Generator as Faker;

$factory->define(Origem::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numberBetween(1, 8888),
        'descricao' => $faker->word,
    ];
});

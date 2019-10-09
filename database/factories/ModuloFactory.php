<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Modulo;
use Faker\Generator as Faker;

$factory->define(Modulo::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
        'descricao' => $faker->name,
    ];
});

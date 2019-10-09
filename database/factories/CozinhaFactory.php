<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cozinha;
use Faker\Generator as Faker;

$factory->define(Cozinha::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
    ];
});

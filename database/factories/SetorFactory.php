<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Setor::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
    ];
});

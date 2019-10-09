<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Integracao;
use Faker\Generator as Faker;

$factory->define(Integracao::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Funcao;
use Faker\Generator as Faker;

$factory->define(Funcao::class, function (Faker $faker) {
    return [
        'descricao' => $faker->unique()->name,
        'remuneracao' => 4.50,
    ];
});

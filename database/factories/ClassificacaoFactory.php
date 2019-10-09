<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Classificacao;
use Faker\Generator as Faker;

$factory->define(Classificacao::class, function (Faker $faker) {
    return [
        'descricao' => $faker->unique()->name,
    ];
});

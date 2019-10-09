<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Unidade;
use Faker\Generator as Faker;

$factory->define(Unidade::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
        'descricao' => $faker->unique()->name,
        'sigla' => $faker->unique()->name,
    ];
});

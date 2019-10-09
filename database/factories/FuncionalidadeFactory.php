<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Funcionalidade;
use Faker\Generator as Faker;

$factory->define(Funcionalidade::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
        'descricao' => $faker->name,
    ];
});

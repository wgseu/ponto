<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Metrica;
use Faker\Generator as Faker;

$factory->define(Metrica::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
        'tipo' => Metrica::TIPO_ENTREGA,
    ];
});

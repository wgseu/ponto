<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Carteira;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Carteira::class, function (Faker $faker) {
    return [
        'tipo' => Carteira::TIPO_BANCARIA,
        'descricao' => $faker->name,
    ];
});

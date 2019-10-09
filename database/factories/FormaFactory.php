<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Forma;
use App\Models\Carteira;
use Faker\Generator as Faker;

$factory->define(Forma::class, function (Faker $faker) {
    $carteira_id = factory(Carteira::class)->create();
    return [
        'tipo' => Forma::TIPO_DINHEIRO,
        'carteira_id' => $carteira_id->id,
        'descricao' => $faker->unique()->name,
    ];
});

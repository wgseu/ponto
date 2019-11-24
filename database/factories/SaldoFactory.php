<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Saldo;
use App\Models\Moeda;
use App\Models\Carteira;
use Faker\Generator as Faker;

$factory->define(Saldo::class, function (Faker $faker) {
    $moeda = factory(Moeda::class)->create();
    $carteira = factory(Carteira::class)->create();
    return [
        'moeda_id' => $moeda->id,
        'carteira_id' => $carteira->id,
    ];
});

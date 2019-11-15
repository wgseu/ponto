<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comanda;
use Faker\Generator as Faker;

$factory->define(Comanda::class, function (Faker $faker) {
    $numero = $faker->unique()->numberBetween(1, 10000);
    return [
        'numero' => $numero,
        'nome' => __('messages.cards_number', ['number' => $numero]),
    ];
});

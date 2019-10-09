<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pagamento;
use App\Models\Carteira;
use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Pagamento::class, function (Faker $faker) {
    $carteira_id = factory(Carteira::class)->create();
    $moeda_id = factory(Moeda::class)->create();
    return [
        'carteira_id' => $carteira_id->id,
        'moeda_id' => $moeda_id->id,
        'valor' => 4.50,
        'lancado' => 4.50,
    ];
});

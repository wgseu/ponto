<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pagamento;
use App\Models\Carteira;
use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Pagamento::class, function (Faker $faker) {
    $carteira = factory(Carteira::class)->create();
    $moeda = factory(Moeda::class)->create();
    return [
        'carteira_id' => $carteira->id,
        'moeda_id' => $moeda->id,
        'valor' => 4.50,
        'lancado' => 4.50,
    ];
});

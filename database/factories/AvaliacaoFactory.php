<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pedido;
use App\Models\Metrica;
use App\Models\Avaliacao;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Avaliacao::class, function (Faker $faker) {
    $metrica = factory(Metrica::class)->create();
    $pedido = factory(Pedido::class)->create();
    return [
        'pedido_id' => $pedido->id,
        'metrica_id' => $metrica->id,
        'estrelas' => $faker->numberBetween(1, 5),
        'data_avaliacao' => Carbon::now(),
    ];
});

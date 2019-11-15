<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Juncao;
use App\Models\Mesa;
use App\Models\Pedido;
use Faker\Generator as Faker;

$factory->define(Juncao::class, function (Faker $faker) {
    $mesa = factory(Mesa::class)->create();
    $pedido = factory(Pedido::class)->create();
    return [
        'mesa_id' => $mesa->id,
        'pedido_id' => $pedido->id,
    ];
});

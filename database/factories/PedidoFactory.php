<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Mesa;
use App\Models\Pedido;
use Faker\Generator as Faker;

$factory->define(Pedido::class, function (Faker $faker) {
    $mesa = factory(Mesa::class)->create();
    return [
        'tipo' => Pedido::TIPO_MESA,
        'mesa_id' => $mesa->id,
    ];
});

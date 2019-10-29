<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Juncao;
use App\Models\Mesa;
use App\Models\Pedido;
use Faker\Generator as Faker;

$factory->define(Juncao::class, function (Faker $faker) {
    $mesa_id = factory(Mesa::class)->create();
    $pedido_id = factory(Pedido::class)->create();
    return [
        'mesa_id' => $mesa_id->id,
        'pedido_id' => $pedido_id->id,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use App\Models\Pedido;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    $pedido_id = factory(Pedido::class)->create();
    return [
        'pedido_id' => $pedido_id->id,
        'preco' => 4.50,
        'quantidade' => 2.30,
        'subtotal' => 4.50,
        'total' => 4.50,
        'preco_venda' => 4.50,
    ];
});

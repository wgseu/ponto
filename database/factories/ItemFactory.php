<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use App\Models\Pedido;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    return [
        'produto_id' => $produto->id,
        'preco' => $produto->preco_venda,
        'quantidade' => 3,
    ];
});

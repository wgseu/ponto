<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Catalogo;
use App\Models\Produto;
use App\Models\Cliente;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Catalogo::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    $fornecedor = factory(Cliente::class)->create();
    return [
        'produto_id' => $produto->id,
        'fornecedor_id' => $fornecedor->id,
        'preco_compra' => 4.50,
    ];
});

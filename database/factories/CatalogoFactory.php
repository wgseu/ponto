<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Catalogo;
use App\Models\Produto;
use App\Models\Fornecedor;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Catalogo::class, function (Faker $faker) {
    $produto_id = factory(Produto::class)->create();
    $fornecedor_id = factory(Fornecedor::class)->create();
    return [
        'produto_id' => $produto_id->id,
        'fornecedor_id' => $fornecedor_id->id,
        'preco_compra' => 4.50,
    ];
});

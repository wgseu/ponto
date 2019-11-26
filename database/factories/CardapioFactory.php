<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cardapio;
use App\Models\Cozinha;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Cardapio::class, function (Faker $faker) {
    $cozinha = factory(Cozinha::class)->create();
    $produto = factory(Produto::class)->create();
    return [
        'cozinha_id' => $cozinha->id,
        'produto_id' => $produto->id,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Requisito;
use App\Models\Lista;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Requisito::class, function (Faker $faker) {
    $lista = factory(Lista::class)->create();
    $produto = factory(Produto::class)->create();
    return [
        'lista_id' => $lista->id,
        'produto_id' => $produto->id,
    ];
});

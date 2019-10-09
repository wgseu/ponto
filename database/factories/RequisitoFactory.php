<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Requisito;
use App\Models\Lista;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Requisito::class, function (Faker $faker) {
    $lista_id = factory(Lista::class)->create();
    $produto_id = factory(Produto::class)->create();
    return [
        'lista_id' => $lista_id->id,
        'produto_id' => $produto_id->id,
    ];
});

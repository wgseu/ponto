<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Estoque;
use App\Models\Produto;
use App\Models\Requisito;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Estoque::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    $setor = factory(Setor::class)->create();
    $requisito = factory(Requisito::class)->create();
    return [
        'produto_id' => $produto->id,
        'setor_id' => $setor->id,
        'requisito_id' => $requisito->id,
        'quantidade' => 3,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Contagem;
use App\Models\Produto;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Contagem::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    $setor = factory(Setor::class)->create();
    return [
        'produto_id' => $produto->id,
        'setor_id' => $setor->id,
        'quantidade' => 2.30,
    ];
});

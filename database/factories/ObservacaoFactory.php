<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Observacao;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Observacao::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    return [
        'produto_id' => $produto->id,
        'descricao' => $faker->unique()->name,
    ];
});

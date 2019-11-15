<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Composicao;
use App\Models\Produto;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Composicao::class, function (Faker $faker) {
    $composicao = factory(Produto::class)->create();
    $produto = factory(Produto::class)->create();
    return [
        'composicao_id' => $composicao->id,
        'produto_id' => $produto->id,
        'quantidade' => 2.30,
    ];
});

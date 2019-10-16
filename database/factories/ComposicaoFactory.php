<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Composicao;
use App\Models\Produto;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Composicao::class, function (Faker $faker) {
    $composicao_id = factory(Produto::class)->create();
    $produto_id = factory(Produto::class)->create();
    return [
        'composicao_id' => $composicao_id->id,
        'produto_id' => $produto_id->id,
        'quantidade' => 2.30,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Contagem;
use App\Models\Produto;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Contagem::class, function (Faker $faker) {
    $produto_id = factory(Produto::class)->create();
    $setor_id = factory(Setor::class)->create();
    return [
        'produto_id' => $produto_id->id,
        'setor_id' => $setor_id->id,
        'quantidade' => 2.30,
    ];
});

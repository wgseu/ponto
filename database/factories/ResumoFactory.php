<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Resumo;
use App\Models\Movimentacao;
use App\Models\Forma;
use Faker\Generator as Faker;

$factory->define(Resumo::class, function (Faker $faker) {
    $movimentacao = factory(Movimentacao::class)->create();
    $forma = factory(Forma::class)->create();
    return [
        'movimentacao_id' => $movimentacao->id,
        'forma_id' => $forma->id,
        'valor' => 4.50,
    ];
});

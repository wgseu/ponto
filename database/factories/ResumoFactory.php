<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Resumo;
use App\Models\Movimentacao;
use App\Models\Forma;
use Faker\Generator as Faker;

$factory->define(Resumo::class, function (Faker $faker) {
    $movimentacao_id = factory(Movimentacao::class)->create();
    $forma_id = factory(Forma::class)->create();
    return [
        'movimentacao_id' => $movimentacao_id->id,
        'forma_id' => $forma_id->id,
        'valor' => 4.50,
    ];
});

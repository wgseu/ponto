<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Origem;
use App\Models\Operacao;
use App\Models\Imposto;
use App\Models\Tributacao;
use Faker\Generator as Faker;

$factory->define(Tributacao::class, function (Faker $faker) {
    $origem = factory(Origem::class)->create();
    $operacao = factory(Operacao::class)->create();
    $imposto = factory(Imposto::class)->create();
    return [
        'ncm' => '87881000',
        'origem_id' => $origem->id,
        'operacao_id' => $operacao->id,
        'imposto_id' => $imposto->id,
    ];
});

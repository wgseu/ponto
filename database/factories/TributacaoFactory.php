<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Origem;
use App\Models\Operacao;
use App\Models\Imposto;
use App\Models\Tributacao;
use Faker\Generator as Faker;

$factory->define(Tributacao::class, function (Faker $faker) {
    $origem_id = factory(Origem::class)->create();
    $operacao_id = factory(Operacao::class)->create();
    $imposto_id = factory(Imposto::class)->create();
    return [
        'ncm' => $faker->name,
        'origem_id' => $origem_id->id,
        'operacao_id' => $operacao_id->id,
        'imposto_id' => $imposto_id->id,
    ];
});

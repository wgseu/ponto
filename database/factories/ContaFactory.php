<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conta;
use App\Models\Classificacao;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Conta::class, function (Faker $faker) {
    $classificacao_id = factory(Classificacao::class)->create();
    $funcionario_id = factory(Prestador::class)->create();
    return [
        'classificacao_id' => $classificacao_id->id,
        'funcionario_id' => $funcionario_id->id,
        'descricao' => $faker->name,
        'valor' => 40,
        'vencimento' => Carbon::now(),
        'data_emissao' => Carbon::now(),
        'frequencia' => 1,
    ];
});

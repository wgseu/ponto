<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movimentacao;
use App\Models\Sessao;
use App\Models\Caixa;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Movimentacao::class, function (Faker $faker) {
    $sessao_id = factory(Sessao::class)->create();
    $caixa_id = factory(Caixa::class)->create();
    $iniciador_id = factory(Prestador::class)->create();
    return [
        'sessao_id' => $sessao_id->id,
        'caixa_id' => $caixa_id->id,
        'iniciador_id' => $iniciador_id->id,
        'data_abertura' => Carbon::now(),
    ];
});

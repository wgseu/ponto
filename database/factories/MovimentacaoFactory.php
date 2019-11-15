<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movimentacao;
use App\Models\Sessao;
use App\Models\Caixa;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Movimentacao::class, function (Faker $faker) {
    $sessao = factory(Sessao::class)->create();
    $caixa = factory(Caixa::class)->create();
    $iniciador = factory(Prestador::class)->create();
    return [
        'sessao_id' => $sessao->id,
        'caixa_id' => $caixa->id,
        'iniciador_id' => $iniciador->id,
        'data_abertura' => Carbon::now(),
    ];
});

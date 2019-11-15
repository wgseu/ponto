<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Auditoria;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Auditoria::class, function (Faker $faker) {
    $prestador = factory(Prestador::class)->create();
    $autorizador = factory(Prestador::class)->create();
    return [
        'prestador_id' => $prestador->id,
        'autorizador_id' => $autorizador->id,
        'tipo' => Auditoria::TIPO_FINANCEIRO,
        'prioridade' => Auditoria::PRIORIDADE_BAIXA,
        'descricao' => $faker->name,
        'data_registro' => Carbon::now(),
    ];
});

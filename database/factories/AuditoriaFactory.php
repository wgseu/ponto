<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Auditoria;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Auditoria::class, function (Faker $faker) {
    $prestador_id = factory(Prestador::class)->create();
    $autorizador_id = factory(Prestador::class)->create();
    return [
        'prestador_id' => $prestador_id->id,
        'autorizador_id' => $autorizador_id->id,
        'tipo' => Auditoria::TIPO_FINANCEIRO,
        'prioridade' => Auditoria::PRIORIDADE_BAIXA,
        'descricao' => $faker->name,
        'data_registro' => Carbon::now(),
    ];
});

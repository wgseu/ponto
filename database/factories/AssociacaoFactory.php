<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Associacao;
use App\Models\Integracao;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Associacao::class, function (Faker $faker) {
    $integracao = factory(Integracao::class)->create();
    return [
        'integracao_id' => $integracao->id,
        'codigo' => $faker->name,
        'cliente' => $faker->name,
        'chave' => $faker->name,
        'pedido' => $faker->name,
        'quantidade' => 2.30,
        'servicos' => 4.50,
        'produtos' => 4.50,
        'descontos' => 4.50,
        'pago' => 4.50,
        'status' => Associacao::STATUS_AGENDADO,
        'sincronizado' => false,
        'integrado' => false,
        'data_pedido' => Carbon::now(),
    ];
});

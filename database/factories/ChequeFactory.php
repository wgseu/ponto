<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cheque;
use App\Models\Cliente;
use App\Models\Banco;
use Faker\Generator as Faker;

$factory->define(Cheque::class, function (Faker $faker) {
    $cliente_id = factory(Cliente::class)->create();
    $banco_id = factory(Banco::class)->create();
    return [
        'cliente_id' => $cliente_id->id,
        'banco_id' => $banco_id->id,
        'agencia' => $faker->name,
        'conta' => $faker->name,
        'numero' => $faker->name,
        'valor' => 4.50,
        'vencimento' => Carbon::now(),
    ];
});

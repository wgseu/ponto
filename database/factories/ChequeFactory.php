<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cheque;
use App\Models\Cliente;
use App\Models\Banco;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Cheque::class, function (Faker $faker) {
    $cliente = factory(Cliente::class)->create();
    $banco = factory(Banco::class)->create();
    return [
        'cliente_id' => $cliente->id,
        'banco_id' => $banco->id,
        'agencia' => $faker->name,
        'conta' => $faker->name,
        'numero' => $faker->name,
        'valor' => 4.50,
        'vencimento' => Carbon::now(),
    ];
});

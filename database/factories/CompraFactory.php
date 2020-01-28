<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Compra;
use App\Models\Prestador;
use App\Models\Cliente;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Compra::class, function (Faker $faker) {
    $comprador = factory(Prestador::class)->create();
    $fornecedor = factory(Cliente::class)->create();
    return [
        'comprador_id' => $comprador->id,
        'fornecedor_id' => $fornecedor->id,
        'data_compra' => Carbon::now(),
    ];
});

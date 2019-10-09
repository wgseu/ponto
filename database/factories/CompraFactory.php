<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Compra;
use App\Models\Prestador;
use App\Models\Fornecedor;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Compra::class, function (Faker $faker) {
    $comprador_id = factory(Prestador::class)->create();
    $fornecedor_id = factory(Fornecedor::class)->create();
    return [
        'comprador_id' => $comprador_id->id,
        'fornecedor_id' => $fornecedor_id->id,
        'data_compra' => Carbon::now(),
    ];
});

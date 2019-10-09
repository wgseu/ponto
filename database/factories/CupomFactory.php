<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cupom;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Cupom::class, function (Faker $faker) {
    return [
        'codigo' => $faker->name,
        'quantidade' => $faker->numberBetween(1, 70),
        'tipo_desconto' => Cupom::TIPO_DESCONTO_VALOR,
        'incluir_servicos' => false,
        'validade' => Carbon::now(),
        'data_registro' => Carbon::now(),
    ];
});

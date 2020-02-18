<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cupom;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Cupom::class, function (Faker $faker) {
    return [
        'codigo' => 'PROMO' . $faker->unique()->randomNumber(6),
        'quantidade' => $faker->numberBetween(1, 70),
        'tipo_desconto' => Cupom::TIPO_DESCONTO_VALOR,
        'valor' => $faker->randomFloat(2, 1, 3),
        'incluir_servicos' => false,
        'validade' => Carbon::now()->addDays(1),
    ];
});

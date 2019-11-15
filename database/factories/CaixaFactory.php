<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Caixa;
use App\Models\Carteira;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Caixa::class, function (Faker $faker) {
    $carteira = factory(Carteira::class)->create(['tipo' => Carteira::TIPO_LOCAL]);
    return [
        'carteira_id' => $carteira->id,
        'descricao' => $faker->unique()->name,
    ];
});

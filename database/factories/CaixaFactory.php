<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Caixa;
use App\Models\Carteira;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Caixa::class, function (Faker $faker) {
    $carteira_id = factory(Carteira::class)->create();
    $carteira_id->tipo = Carteira::TIPO_LOCAL;
    $carteira_id->save();
    return [
        'carteira_id' => $carteira_id->id,
        'descricao' => $faker->unique()->name,
    ];
});

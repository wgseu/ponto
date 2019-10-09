<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pontuacao;
use App\Models\Promocao;
use Faker\Generator as Faker;

$factory->define(Pontuacao::class, function (Faker $faker) {
    $promocao_id = factory(Promocao::class)->create();
    return [
        'promocao_id' => $promocao_id->id,
        'quantidade' => $faker->numberBetween(1, 70),
    ];
});

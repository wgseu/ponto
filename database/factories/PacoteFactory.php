<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pacote;
use App\Models\Produto;
use App\Models\Grupo;
use Faker\Generator as Faker;

$factory->define(Pacote::class, function (Faker $faker) {
    $pacote = factory(Produto::class)->create();
    $grupo = factory(Grupo::class)->create();
    return [
        'pacote_id' => $pacote->id,
        'grupo_id' => $grupo->id,
        'acrescimo' => 4.50,
    ];
});

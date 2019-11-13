<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pacote;
use App\Models\Produto;
use App\Models\Grupo;
use Faker\Generator as Faker;

$factory->define(Pacote::class, function (Faker $faker) {
    $grupo_id = factory(Grupo::class)->create();
    return [
        'pacote_id' => $grupo_id->produto->id,
        'grupo_id' => $grupo_id->id,
        'acrescimo' => 4.50,
    ];
});

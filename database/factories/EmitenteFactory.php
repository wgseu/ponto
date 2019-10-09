<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Emitente;
use App\Models\Regime;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Emitente::class, function (Faker $faker) {
    $regime_id = factory(Regime::class)->create();
    return [
        'regime_id' => $regime_id->id,
    ];
});

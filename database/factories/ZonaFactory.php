<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Zona;
use App\Models\Bairro;
use Faker\Generator as Faker;

$factory->define(Zona::class, function (Faker $faker) {
    $bairro = factory(Bairro::class)->create();
    return [
        'bairro_id' => $bairro->id,
        'nome' => $faker->unique()->name,
        'adicional_entrega' => 4.50,
    ];
});

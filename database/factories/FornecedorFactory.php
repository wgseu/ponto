<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fornecedor;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Fornecedor::class, function (Faker $faker) {
    $empresa = factory(Cliente::class)->create();
    return [
        'empresa_id' => $empresa->id,
    ];
});

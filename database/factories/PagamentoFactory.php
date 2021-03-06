<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Forma;
use App\Models\Pagamento;
use Faker\Generator as Faker;

$factory->define(Pagamento::class, function (Faker $faker) {
    $moeda = app('currency');
    $forma = factory(Forma::class)->create();
    
    return [
        'moeda_id' => $moeda->id,
        'forma_id' => $forma->id,
        'lancado' => 4.50,
    ];
});

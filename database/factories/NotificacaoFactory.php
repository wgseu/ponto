<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cliente;
use App\Models\Notificacao;
use Faker\Generator as Faker;

$factory->define(Notificacao::class, function (Faker $faker) {
    $destinatario = factory(Cliente::class)->create();
    return [
        'destinatario_id' => $destinatario->id,
        'mensagem' => $faker->name,
    ];
});

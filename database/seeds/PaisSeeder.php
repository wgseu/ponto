<?php

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Pais([
            'nome' => __('messages.brazil'),
            'sigla' => 'BRA',
            'codigo' => 'BR',
            'moeda_id' => 1,
            'idioma' => 'pt-BR',
            'prefixo' => null,
            'unitario' => 0,
            'entradas' => json_encode([
                'phone' => [
                    'mask' => '(##) ####-####|(##) #####-####',
                ],
                'zipcode' => [
                    'mask' => '#####-###',
                ],
                'cpf' => [
                    'mask' => '###.###.###-##',
                ],
                'cnpj' => [
                    'mask' => '##.###.###/####-##',
                ],
                'ncm' => [
                    'mask' => '####.##.##',
                ],
            ]),
        ]))->save();
        (new Pais([
            'nome' => __('messages.united_states'),
            'sigla' => 'USA',
            'codigo' => 'US',
            'moeda_id' => 2,
            'idioma' => 'en-US',
            'prefixo' => null,
            'entradas' => 'W1RpdHVsb10NCkNFUD1aSVANCkNQRj1TU04NCg0KW01hc2NhcmFdDQpDRVA9OTk5OTkNCkNQRj05OTktOTktOTk5OQ0KVGVsZWZvbmU9KDk5OSkgOTk5LTk5OTkNCg0KW0F1ZGl0b3JpYV0NCg0K',
            'unitario' => 0
        ]))->save();
        (new Pais([
            'nome' => __('messages.spain'),
            'sigla' => 'ESP',
            'codigo' => 'ES',
            'moeda_id' => 3,
            'idioma' => 'es-ES',
            'prefixo' => null,
            'entradas' => 'W1RpdHVsb10NCkNOUEo9UlVUDQpDUEY9TlVJUA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNQRj05Ljk5OS45OTkuOTk5DQpDRVA9OTk5OTkNCkNOUEo9OTkuOTk5Ljk5OS05DQpUZWxlZm9uZT05OTkgOTk5IDk5OQ0KDQo=',
            'unitario' => 1
        ]))->save();
        (new Pais([
            'nome' => __('messages.mozambique'),
            'sigla' => 'MOZ',
            'codigo' => 'MZ',
            'moeda_id' => 4,
            'idioma' => 'pt-BR',
            'prefixo' => null,
            'entradas' => 'W1RpdHVsb10NCkNOUEo9TlVJVA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNOUEo9OS45OTk5OTk5LTkNCkNFUD05OTk5DQpUZWxlZm9uZT05OTk5LTk5OTk5DQoNCg==',
            'unitario' => 1
        ]))->save();
    }
}

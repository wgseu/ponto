<?php

use App\Models\Estado;
use App\Models\Pais;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brasil = Pais::where('nome', __('messages.brazil'))->first()->id;

        (new Estado([
            'nome' => 'Acre',
            'uf' => 'AC',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Alagoas',
            'uf' => 'AL',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Amazonas',
            'uf' => 'AM',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Amapá',
            'uf' => 'AP',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Bahia',
            'uf' => 'BA',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Ceará',
            'uf' => 'CE',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Brasília',
            'uf' => 'DF',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Espírito Santo',
            'uf' => 'ES',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Goiás',
            'uf' => 'GO',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Maranhão',
            'uf' => 'MA',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Minas Gerais',
            'uf' => 'MG',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Mato Grosso do Sul',
            'uf' => 'MS',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Mato Grosso',
            'uf' => 'MT',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Pará',
            'uf' => 'PA',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Paraíba',
            'uf' => 'PB',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Pernambuco',
            'uf' => 'PE',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Piauí',
            'uf' => 'PI',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Paraná',
            'uf' => 'PR',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Rio de Janeiro',
            'uf' => 'RJ',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Rio Grande do Norte',
            'uf' => 'RN',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Rondônia',
            'uf' => 'RO',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Roraima',
            'uf' => 'RR',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Rio Grande do Sul',
            'uf' => 'RS',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Santa Catarina',
            'uf' => 'SC',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Sergipe',
            'uf' => 'SE',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'São Paulo',
            'uf' => 'SP',
            'pais_id' => $brasil,
        ]))->save();

        (new Estado([
            'nome' => 'Tocantins',
            'uf' => 'TO',
            'pais_id' => $brasil,
        ]))->save();
    }
}

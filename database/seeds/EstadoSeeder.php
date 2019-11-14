<?php

use App\Models\Estado;
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
        (new Estado([
            'id' => 1,
            'nome' => 'Acre',
            'uf' => 'AC',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 2,
            'nome' => 'Alagoas',
            'uf' => 'AL',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 3,
            'nome' => 'Amazonas',
            'uf' => 'AM',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 4,
            'nome' => 'Amapá',
            'uf' => 'AP',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 5,
            'nome' => 'Bahia',
            'uf' => 'BA',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 6,
            'nome' => 'Ceará',
            'uf' => 'CE',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 7,
            'nome' => 'Brasília',
            'uf' => 'DF',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 8,
            'nome' => 'Espírito Santo',
            'uf' => 'ES',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 9,
            'nome' => 'Goiás',
            'uf' => 'GO',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 10,
            'nome' => 'Maranhão',
            'uf' => 'MA',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 11,
            'nome' => 'Minas Gerais',
            'uf' => 'MG',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 12,
            'nome' => 'Mato Grosso do Sul',
            'uf' => 'MS',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 13,
            'nome' => 'Mato Grosso',
            'uf' => 'MT',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 14,
            'nome' => 'Pará',
            'uf' => 'PA',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 15,
            'nome' => 'Paraíba',
            'uf' => 'PB',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 16,
            'nome' => 'Pernambuco',
            'uf' => 'PE',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 17,
            'nome' => 'Piauí',
            'uf' => 'PI',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 18,
            'nome' => 'Paraná',
            'uf' => 'PR',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 19,
            'nome' => 'Rio de Janeiro',
            'uf' => 'RJ',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 20,
            'nome' => 'Rio Grande do Norte',
            'uf' => 'RN',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 21,
            'nome' => 'Rondônia',
            'uf' => 'RO',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 22,
            'nome' => 'Roraima',
            'uf' => 'RR',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 23,
            'nome' => 'Rio Grande do Sul',
            'uf' => 'RS',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 24,
            'nome' => 'Santa Catarina',
            'uf' => 'SC',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 25,
            'nome' => 'Sergipe',
            'uf' => 'SE',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 26,
            'nome' => 'São Paulo',
            'uf' => 'SP',
            'pais_id' => 1,
        ]))->save();

        (new Estado([
            'id' => 27,
            'nome' => 'Tocantins',
            'uf' => 'TO',
            'pais_id' => 1,
        ]))->save();
    }
}

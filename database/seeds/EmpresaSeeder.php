<?php

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use Carbon\Carbon;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Empresa([
            'fantasia' => 'Empresa teste',
            'email' => 'empresa@teste.com.br',
            'senha' => 'Teste123',
            'data_criacao' => Carbon::now(),
            'cnpj' => '06794744000101',
            'fone1' => '44999719966'
        ]))->save();
    }
}

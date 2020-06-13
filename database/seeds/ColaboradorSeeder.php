<?php

use App\Models\Colaborador;
use App\Models\Empresa;
use Illuminate\Database\Seeder;

class ColaboradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = Empresa::find(1);
        (new Colaborador([
            'empresa_id' => $empresa->id,
            'nome' => 'Fulano',
            'sobrenome' => 'Silva',
            'email' => 'colaborador@teste.com.br',
            'senha' => 'Teste123',
            'carga_horaria' => 220,
            'status' => 'Trabalho',
            'ativo' => true,
        ]))->save();
    }
}

<?php

use App\Models\Funcionalidade;
use Illuminate\Database\Seeder;

class FuncionalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$[table.each]
        (new Funcionalidade([
            'id' => $[table.id],
            'modulo_id' => $[table.style],
            'nome' => __('messages.$[table.norm]_functionality_name'),
            'descricao' => __('messages.$[table.norm]_functionality_description'),
        ]))->save();
$[table.end]
    }
}

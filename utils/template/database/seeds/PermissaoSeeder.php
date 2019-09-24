<?php

use App\Models\Permissao;
use Illuminate\Database\Seeder;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$[table.each]
$[table.if(first)]
$[table.else]

$[table.end]
        (new Permissao([
            'funcionalidade_id' => $[table.id],
            'nome' => '$[table.norm]:view',
            'descricao' => __('messages.$[table.norm]_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => $[table.id],
            'nome' => '$[table.norm]:create',
            'descricao' => __('messages.$[table.norm]_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => $[table.id],
            'nome' => '$[table.norm]:update',
            'descricao' => __('messages.$[table.norm]_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => $[table.id],
            'nome' => '$[table.norm]:delete',
            'descricao' => __('messages.$[table.norm]_delete_permition'),
        ]))->save();
$[table.end]
    }
}

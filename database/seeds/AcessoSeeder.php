<?php

use App\Models\Acesso;
use App\Models\Funcao;
use App\Models\Permissao;
use Illuminate\Database\Seeder;

class AcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $funcao_admin = Funcao::where('descricao', __('messages.administrator'))->first();
        $funcao_waiter = Funcao::where('descricao', __('messages.waiter'))->first();
        $funcao_cash_operator = Funcao::where('descricao', __('messages.cash_operator'))->first();
        $funcao_cooker = Funcao::where('descricao', __('messages.cooker'))->first();
        $funcao_deliveryman = Funcao::where('descricao', __('messages.deliveryman'))->first();
        $funcao_stockist = Funcao::where('descricao', __('messages.stockist'))->first();

        $setor_view_permition = Permissao::where('nome', 'setor:view')->first();
        $setor_create_permition = Permissao::where('nome', 'setor:create')->first();
        $setor_update_permition = Permissao::where('nome', 'setor:update')->first();
        $setor_delete_permition = Permissao::where('nome', 'setor:delete')->first();
        $mesa_view_permition = Permissao::where('nome', 'mesa:view')->first();
        $mesa_create_permition = Permissao::where('nome', 'mesa:create')->first();
        $mesa_update_permition = Permissao::where('nome', 'mesa:update')->first();
        $mesa_delete_permition = Permissao::where('nome', 'mesa:delete')->first();
        $sessao_view_permition = Permissao::where('nome', 'sessao:view')->first();
        $sessao_create_permition = Permissao::where('nome', 'sessao:create')->first();
        $sessao_update_permition = Permissao::where('nome', 'sessao:update')->first();
        $sessao_delete_permition = Permissao::where('nome', 'sessao:delete')->first();
        $banco_view_permition = Permissao::where('nome', 'banco:view')->first();
        $banco_create_permition = Permissao::where('nome', 'banco:create')->first();
        $banco_update_permition = Permissao::where('nome', 'banco:update')->first();
        $banco_delete_permition = Permissao::where('nome', 'banco:delete')->first();
        $carteira_view_permition = Permissao::where('nome', 'carteira:view')->first();
        $carteira_create_permition = Permissao::where('nome', 'carteira:create')->first();
        $carteira_update_permition = Permissao::where('nome', 'carteira:update')->first();
        $carteira_delete_permition = Permissao::where('nome', 'carteira:delete')->first();
        $caixa_view_permition = Permissao::where('nome', 'caixa:view')->first();
        $caixa_create_permition = Permissao::where('nome', 'caixa:create')->first();
        $caixa_update_permition = Permissao::where('nome', 'caixa:update')->first();
        $caixa_delete_permition = Permissao::where('nome', 'caixa:delete')->first();
        $forma_view_permition = Permissao::where('nome', 'forma:view')->first();
        $forma_create_permition = Permissao::where('nome', 'forma:create')->first();
        $forma_update_permition = Permissao::where('nome', 'forma:update')->first();
        $forma_delete_permition = Permissao::where('nome', 'forma:delete')->first();
        $cartao_view_permition = Permissao::where('nome', 'cartao:view')->first();
        $cartao_create_permition = Permissao::where('nome', 'cartao:create')->first();
        $cartao_update_permition = Permissao::where('nome', 'cartao:update')->first();
        $cartao_delete_permition = Permissao::where('nome', 'cartao:delete')->first();
        $funcao_view_permition = Permissao::where('nome', 'funcao:view')->first();
        $funcao_create_permition = Permissao::where('nome', 'funcao:create')->first();
        $funcao_update_permition = Permissao::where('nome', 'funcao:update')->first();
        $funcao_delete_permition = Permissao::where('nome', 'funcao:delete')->first();
        $cliente_view_permition = Permissao::where('nome', 'cliente:view')->first();
        $cliente_create_permition = Permissao::where('nome', 'cliente:create')->first();
        $cliente_update_permition = Permissao::where('nome', 'cliente:update')->first();
        $cliente_delete_permition = Permissao::where('nome', 'cliente:delete')->first();
        $prestador_view_permition = Permissao::where('nome', 'prestador:view')->first();
        $prestador_create_permition = Permissao::where('nome', 'prestador:create')->first();
        $prestador_update_permition = Permissao::where('nome', 'prestador:update')->first();
        $prestador_delete_permition = Permissao::where('nome', 'prestador:delete')->first();
        $moeda_view_permition = Permissao::where('nome', 'moeda:view')->first();
        $moeda_create_permition = Permissao::where('nome', 'moeda:create')->first();
        $moeda_update_permition = Permissao::where('nome', 'moeda:update')->first();
        $moeda_delete_permition = Permissao::where('nome', 'moeda:delete')->first();
        $pais_view_permition = Permissao::where('nome', 'pais:view')->first();
        $pais_create_permition = Permissao::where('nome', 'pais:create')->first();
        $pais_update_permition = Permissao::where('nome', 'pais:update')->first();
        $pais_delete_permition = Permissao::where('nome', 'pais:delete')->first();
        $estado_view_permition = Permissao::where('nome', 'estado:view')->first();
        $estado_create_permition = Permissao::where('nome', 'estado:create')->first();
        $estado_update_permition = Permissao::where('nome', 'estado:update')->first();
        $estado_delete_permition = Permissao::where('nome', 'estado:delete')->first();
        $cidade_view_permition = Permissao::where('nome', 'cidade:view')->first();
        $cidade_create_permition = Permissao::where('nome', 'cidade:create')->first();
        $cidade_update_permition = Permissao::where('nome', 'cidade:update')->first();
        $cidade_delete_permition = Permissao::where('nome', 'cidade:delete')->first();
        $bairro_view_permition = Permissao::where('nome', 'bairro:view')->first();
        $bairro_create_permition = Permissao::where('nome', 'bairro:create')->first();
        $bairro_update_permition = Permissao::where('nome', 'bairro:update')->first();
        $bairro_delete_permition = Permissao::where('nome', 'bairro:delete')->first();
        $zona_view_permition = Permissao::where('nome', 'zona:view')->first();
        $zona_create_permition = Permissao::where('nome', 'zona:create')->first();
        $zona_update_permition = Permissao::where('nome', 'zona:update')->first();
        $zona_delete_permition = Permissao::where('nome', 'zona:delete')->first();
        $localizacao_view_permition = Permissao::where('nome', 'localizacao:view')->first();
        $localizacao_create_permition = Permissao::where('nome', 'localizacao:create')->first();
        $localizacao_update_permition = Permissao::where('nome', 'localizacao:update')->first();
        $localizacao_delete_permition = Permissao::where('nome', 'localizacao:delete')->first();
        $comanda_view_permition = Permissao::where('nome', 'comanda:view')->first();
        $comanda_create_permition = Permissao::where('nome', 'comanda:create')->first();
        $comanda_update_permition = Permissao::where('nome', 'comanda:update')->first();
        $comanda_delete_permition = Permissao::where('nome', 'comanda:delete')->first();
        $viagem_view_permition = Permissao::where('nome', 'viagem:view')->first();
        $viagem_create_permition = Permissao::where('nome', 'viagem:create')->first();
        $viagem_update_permition = Permissao::where('nome', 'viagem:update')->first();
        $viagem_delete_permition = Permissao::where('nome', 'viagem:delete')->first();
        $integracao_view_permition = Permissao::where('nome', 'integracao:view')->first();
        $integracao_create_permition = Permissao::where('nome', 'integracao:create')->first();
        $integracao_update_permition = Permissao::where('nome', 'integracao:update')->first();
        $integracao_delete_permition = Permissao::where('nome', 'integracao:delete')->first();
        $associacao_view_permition = Permissao::where('nome', 'associacao:view')->first();
        $associacao_create_permition = Permissao::where('nome', 'associacao:create')->first();
        $associacao_update_permition = Permissao::where('nome', 'associacao:update')->first();
        $associacao_delete_permition = Permissao::where('nome', 'associacao:delete')->first();
        $pedido_view_permition = Permissao::where('nome', 'pedido:view')->first();
        $pedido_create_permition = Permissao::where('nome', 'pedido:create')->first();
        $pedido_update_permition = Permissao::where('nome', 'pedido:update')->first();
        $pedido_delete_permition = Permissao::where('nome', 'pedido:delete')->first();
        $categoria_view_permition = Permissao::where('nome', 'categoria:view')->first();
        $categoria_create_permition = Permissao::where('nome', 'categoria:create')->first();
        $categoria_update_permition = Permissao::where('nome', 'categoria:update')->first();
        $categoria_delete_permition = Permissao::where('nome', 'categoria:delete')->first();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $setor_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $setor_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $mesa_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $mesa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $mesa_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $mesa_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $sessao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $sessao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $sessao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $sessao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $banco_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $banco_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $banco_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $banco_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $carteira_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $carteira_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $carteira_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $carteira_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $caixa_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $caixa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $caixa_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $caixa_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $forma_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $forma_create_permition->id,
        ]))->save();
        
        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $forma_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $forma_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cartao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cartao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cartao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cartao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $funcao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $funcao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $funcao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $funcao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cliente_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cliente_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cliente_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cliente_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $prestador_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $prestador_create_permition->id,
        ]))->save();
        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $prestador_update_permition->id,
        ]))->save();
        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $prestador_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $moeda_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $moeda_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $moeda_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $moeda_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pais_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pais_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pais_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pais_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $estado_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $estado_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $estado_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $estado_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cidade_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cidade_create_permition->id,
        ]))->save();


        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cidade_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $cidade_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $bairro_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $bairro_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $bairro_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $bairro_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $zona_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $zona_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $zona_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $zona_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $localizacao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $localizacao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $localizacao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $localizacao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $comanda_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $comanda_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $comanda_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $comanda_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $viagem_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $viagem_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $viagem_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $viagem_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $integracao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $integracao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $integracao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $integracao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $associacao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $associacao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $associacao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $associacao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pedido_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pedido_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pedido_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $pedido_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $categoria_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $categoria_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $categoria_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_admin->id,
            'permissao_id' => $categoria_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' =>  $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' =>  $setor_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $mesa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $mesa_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $banco_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $caixa_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $caixa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $forma_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $prestador_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $estado_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $cidade_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $comanda_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $categoria_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_waiter->id,
            'permissao_id' => $categoria_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' =>  $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' =>  $setor_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $mesa_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $mesa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $mesa_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $sessao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $sessao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $sessao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $banco_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $banco_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $banco_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $caixa_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $caixa_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $forma_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $forma_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $prestador_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $prestador_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $prestador_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $prestador_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $moeda_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $moeda_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $moeda_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $pais_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $pais_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $estado_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $cidade_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $cidade_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $cidade_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $localizacao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $comanda_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $comanda_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $viagem_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $integracao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $pedido_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $pedido_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $categoria_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $categoria_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cash_operator->id,
            'permissao_id' => $categoria_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cooker->id,
            'permissao_id' =>  $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_cooker->id,
            'permissao_id' => $localizacao_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' =>  $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' => $prestador_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' => $moeda_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' => $zona_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' => $localizacao_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_deliveryman->id,
            'permissao_id' => $categoria_update_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' =>  $setor_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $carteira_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $forma_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $cartao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $zona_create_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $localizacao_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $viagem_delete_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $integracao_view_permition->id,
        ]))->save();

        (new Acesso([
            'funcao_id' => $funcao_stockist->id,
            'permissao_id' => $categoria_delete_permition->id,
        ]))->save();
    }
}

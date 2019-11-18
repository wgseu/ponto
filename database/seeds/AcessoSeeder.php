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

        $setor_view_permition = Permissao::where('descricao', __('messages.setor_view_permition'))->first();
        $setor_create_permition = Permissao::where('descricao', __('messages.setor_create_permition'))->first();
        $setor_update_permition = Permissao::where('descricao', __('messages.setor_update_permition'))->first();
        $setor_delete_permition = Permissao::where('descricao', __('messages.setor_delete_permition'))->first();
        $mesa_view_permition = Permissao::where('descricao', __('messages.mesa_view_permition'))->first();
        $mesa_create_permition = Permissao::where('descricao', __('messages.mesa_create_permition'))->first();
        $mesa_update_permition = Permissao::where('descricao', __('messages.mesa_update_permition'))->first();
        $mesa_delete_permition = Permissao::where('descricao', __('messages.mesa_delete_permition'))->first();
        $sessao_view_permition = Permissao::where('descricao', __('messages.sessao_view_permition'))->first();
        $sessao_create_permition = Permissao::where('descricao', __('messages.sessao_create_permition'))->first();
        $sessao_update_permition = Permissao::where('descricao', __('messages.sessao_update_permition'))->first();
        $sessao_delete_permition = Permissao::where('descricao', __('messages.sessao_delete_permition'))->first();
        $banco_view_permition = Permissao::where('descricao', __('messages.banco_view_permition'))->first();
        $banco_create_permition = Permissao::where('descricao', __('messages.banco_create_permition'))->first();
        $banco_update_permition = Permissao::where('descricao', __('messages.banco_update_permition'))->first();
        $banco_delete_permition = Permissao::where('descricao', __('messages.banco_delete_permition'))->first();
        $carteira_view_permition = Permissao::where('descricao', __('messages.carteira_view_permition'))->first();
        $carteira_create_permition = Permissao::where('descricao', __('messages.carteira_create_permition'))->first();
        $carteira_update_permition = Permissao::where('descricao', __('messages.carteira_update_permition'))->first();
        $carteira_delete_permition = Permissao::where('descricao', __('messages.carteira_delete_permition'))->first();
        $caixa_view_permition = Permissao::where('descricao', __('messages.caixa_view_permition'))->first();
        $caixa_create_permition = Permissao::where('descricao', __('messages.caixa_create_permition'))->first();
        $caixa_update_permition = Permissao::where('descricao', __('messages.caixa_update_permition'))->first();
        $caixa_delete_permition = Permissao::where('descricao', __('messages.caixa_delete_permition'))->first();
        $forma_view_permition = Permissao::where('descricao', __('messages.forma_view_permition'))->first();
        $forma_create_permition = Permissao::where('descricao', __('messages.forma_create_permition'))->first();
        $forma_update_permition = Permissao::where('descricao', __('messages.forma_update_permition'))->first();
        $forma_delete_permition = Permissao::where('descricao', __('messages.forma_delete_permition'))->first();
        $cartao_view_permition = Permissao::where('descricao', __('messages.cartao_view_permition'))->first();
        $cartao_create_permition = Permissao::where('descricao', __('messages.cartao_create_permition'))->first();
        $cartao_update_permition = Permissao::where('descricao', __('messages.cartao_update_permition'))->first();
        $cartao_delete_permition = Permissao::where('descricao', __('messages.cartao_delete_permition'))->first();
        $funcao_view_permition = Permissao::where('descricao', __('messages.funcao_view_permition'))->first();
        $funcao_create_permition = Permissao::where('descricao', __('messages.funcao_create_permition'))->first();
        $funcao_update_permition = Permissao::where('descricao', __('messages.funcao_update_permition'))->first();
        $funcao_delete_permition = Permissao::where('descricao', __('messages.funcao_delete_permition'))->first();
        $cliente_view_permition = Permissao::where('descricao', __('messages.cliente_view_permition'))->first();
        $cliente_create_permition = Permissao::where('descricao', __('messages.cliente_create_permition'))->first();
        $cliente_update_permition = Permissao::where('descricao', __('messages.cliente_update_permition'))->first();
        $cliente_delete_permition = Permissao::where('descricao', __('messages.cliente_delete_permition'))->first();
        $prestador_view_permition = Permissao::where('descricao', __('messages.prestador_view_permition'))->first();
        $prestador_create_permition = Permissao::where('descricao', __('messages.prestador_create_permition'))->first();
        $prestador_update_permition = Permissao::where('descricao', __('messages.prestador_update_permition'))->first();
        $prestador_delete_permition = Permissao::where('descricao', __('messages.prestador_delete_permition'))->first();
        $moeda_view_permition = Permissao::where('descricao', __('messages.moeda_view_permition'))->first();
        $moeda_create_permition = Permissao::where('descricao', __('messages.moeda_create_permition'))->first();
        $moeda_update_permition = Permissao::where('descricao', __('messages.moeda_update_permition'))->first();
        $moeda_delete_permition = Permissao::where('descricao', __('messages.moeda_delete_permition'))->first();
        $pais_view_permition = Permissao::where('descricao', __('messages.pais_view_permition'))->first();
        $pais_create_permition = Permissao::where('descricao', __('messages.pais_create_permition'))->first();
        $pais_update_permition = Permissao::where('descricao', __('messages.pais_update_permition'))->first();
        $pais_delete_permition = Permissao::where('descricao', __('messages.pais_delete_permition'))->first();
        $estado_view_permition = Permissao::where('descricao', __('messages.estado_view_permition'))->first();
        $estado_create_permition = Permissao::where('descricao', __('messages.estado_create_permition'))->first();
        $estado_update_permition = Permissao::where('descricao', __('messages.estado_update_permition'))->first();
        $estado_delete_permition = Permissao::where('descricao', __('messages.estado_delete_permition'))->first();
        $cidade_view_permition = Permissao::where('descricao', __('messages.cidade_view_permition'))->first();
        $cidade_create_permition = Permissao::where('descricao', __('messages.cidade_create_permition'))->first();
        $cidade_update_permition = Permissao::where('descricao', __('messages.cidade_update_permition'))->first();
        $cidade_delete_permition = Permissao::where('descricao', __('messages.cidade_delete_permition'))->first();
        $bairro_view_permition = Permissao::where('descricao', __('messages.bairro_view_permition'))->first();
        $bairro_create_permition = Permissao::where('descricao', __('messages.bairro_create_permition'))->first();
        $bairro_update_permition = Permissao::where('descricao', __('messages.bairro_update_permition'))->first();
        $bairro_delete_permition = Permissao::where('descricao', __('messages.bairro_delete_permition'))->first();
        $zona_view_permition = Permissao::where('descricao', __('messages.zona_view_permition'))->first();
        $zona_create_permition = Permissao::where('descricao', __('messages.zona_create_permition'))->first();
        $zona_update_permition = Permissao::where('descricao', __('messages.zona_update_permition'))->first();
        $zona_delete_permition = Permissao::where('descricao', __('messages.zona_delete_permition'))->first();
        $localizacao_view_permition = Permissao::where('descricao', __('messages.localizacao_view_permition'))->first();
        $localizacao_create_permition = Permissao::where('descricao', __('messages.localizacao_create_permition'))->first();
        $localizacao_update_permition = Permissao::where('descricao', __('messages.localizacao_update_permition'))->first();
        $localizacao_delete_permition = Permissao::where('descricao', __('messages.localizacao_delete_permition'))->first();
        $comanda_view_permition = Permissao::where('descricao', __('messages.comanda_view_permition'))->first();
        $comanda_create_permition = Permissao::where('descricao', __('messages.comanda_create_permition'))->first();
        $comanda_update_permition = Permissao::where('descricao', __('messages.comanda_update_permition'))->first();
        $comanda_delete_permition = Permissao::where('descricao', __('messages.comanda_delete_permition'))->first();
        $viagem_view_permition = Permissao::where('descricao', __('messages.viagem_view_permition'))->first();
        $viagem_create_permition = Permissao::where('descricao', __('messages.viagem_create_permition'))->first();
        $viagem_update_permition = Permissao::where('descricao', __('messages.viagem_update_permition'))->first();
        $viagem_delete_permition = Permissao::where('descricao', __('messages.viagem_delete_permition'))->first();
        $integracao_view_permition = Permissao::where('descricao', __('messages.integracao_view_permition'))->first();
        $integracao_create_permition = Permissao::where('descricao', __('messages.integracao_create_permition'))->first();
        $integracao_update_permition = Permissao::where('descricao', __('messages.integracao_update_permition'))->first();
        $integracao_delete_permition = Permissao::where('descricao', __('messages.integracao_delete_permition'))->first();
        $associacao_view_permition = Permissao::where('descricao', __('messages.associacao_view_permition'))->first();
        $associacao_create_permition = Permissao::where('descricao', __('messages.associacao_create_permition'))->first();
        $associacao_update_permition = Permissao::where('descricao', __('messages.associacao_update_permition'))->first();
        $associacao_delete_permition = Permissao::where('descricao', __('messages.associacao_delete_permition'))->first();
        $pedido_view_permition = Permissao::where('descricao', __('messages.pedido_view_permition'))->first();
        $pedido_create_permition = Permissao::where('descricao', __('messages.pedido_create_permition'))->first();
        $pedido_update_permition = Permissao::where('descricao', __('messages.pedido_update_permition'))->first();
        $pedido_delete_permition = Permissao::where('descricao', __('messages.pedido_delete_permition'))->first();
        $categoria_view_permition = Permissao::where('descricao', __('messages.categoria_view_permition'))->first();
        $categoria_create_permition = Permissao::where('descricao', __('messages.categoria_create_permition'))->first();
        $categoria_update_permition = Permissao::where('descricao', __('messages.categoria_update_permition'))->first();
        $categoria_delete_permition = Permissao::where('descricao', __('messages.categoria_delete_permition'))->first();

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

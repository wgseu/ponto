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

        (new Permissao([
            'funcionalidade_id' => 71,
            'nome' => 'setor:view',
            'descricao' => __('messages.setor_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 71,
            'nome' => 'setor:create',
            'descricao' => __('messages.setor_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 71,
            'nome' => 'setor:update',
            'descricao' => __('messages.setor_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 71,
            'nome' => 'setor:delete',
            'descricao' => __('messages.setor_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 46,
            'nome' => 'mesa:view',
            'descricao' => __('messages.mesa_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 46,
            'nome' => 'mesa:create',
            'descricao' => __('messages.mesa_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 46,
            'nome' => 'mesa:update',
            'descricao' => __('messages.mesa_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 46,
            'nome' => 'mesa:delete',
            'descricao' => __('messages.mesa_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 70,
            'nome' => 'sessao:view',
            'descricao' => __('messages.sessao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 70,
            'nome' => 'sessao:create',
            'descricao' => __('messages.sessao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 70,
            'nome' => 'sessao:update',
            'descricao' => __('messages.sessao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 70,
            'nome' => 'sessao:delete',
            'descricao' => __('messages.sessao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 6,
            'nome' => 'banco:view',
            'descricao' => __('messages.banco_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 6,
            'nome' => 'banco:create',
            'descricao' => __('messages.banco_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 6,
            'nome' => 'banco:update',
            'descricao' => __('messages.banco_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 6,
            'nome' => 'banco:delete',
            'descricao' => __('messages.banco_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 9,
            'nome' => 'carteira:view',
            'descricao' => __('messages.carteira_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 9,
            'nome' => 'carteira:create',
            'descricao' => __('messages.carteira_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 9,
            'nome' => 'carteira:update',
            'descricao' => __('messages.carteira_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 9,
            'nome' => 'carteira:delete',
            'descricao' => __('messages.carteira_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 7,
            'nome' => 'caixa:view',
            'descricao' => __('messages.caixa_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 7,
            'nome' => 'caixa:create',
            'descricao' => __('messages.caixa_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 7,
            'nome' => 'caixa:update',
            'descricao' => __('messages.caixa_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 7,
            'nome' => 'caixa:delete',
            'descricao' => __('messages.caixa_delete_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 7,
            'nome' => 'caixa:reopen',
            'descricao' => __('messages.caixa_reopen_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 33,
            'nome' => 'forma:view',
            'descricao' => __('messages.forma_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 33,
            'nome' => 'forma:create',
            'descricao' => __('messages.forma_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 33,
            'nome' => 'forma:update',
            'descricao' => __('messages.forma_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 33,
            'nome' => 'forma:delete',
            'descricao' => __('messages.forma_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 10,
            'nome' => 'cartao:view',
            'descricao' => __('messages.cartao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 10,
            'nome' => 'cartao:create',
            'descricao' => __('messages.cartao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 10,
            'nome' => 'cartao:update',
            'descricao' => __('messages.cartao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 10,
            'nome' => 'cartao:delete',
            'descricao' => __('messages.cartao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 36,
            'nome' => 'funcao:view',
            'descricao' => __('messages.funcao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 36,
            'nome' => 'funcao:create',
            'descricao' => __('messages.funcao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 36,
            'nome' => 'funcao:update',
            'descricao' => __('messages.funcao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 36,
            'nome' => 'funcao:delete',
            'descricao' => __('messages.funcao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 16,
            'nome' => 'cliente:view',
            'descricao' => __('messages.cliente_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 16,
            'nome' => 'cliente:create',
            'descricao' => __('messages.cliente_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 16,
            'nome' => 'cliente:update',
            'descricao' => __('messages.cliente_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 16,
            'nome' => 'cliente:delete',
            'descricao' => __('messages.cliente_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 62,
            'nome' => 'prestador:view',
            'descricao' => __('messages.prestador_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 62,
            'nome' => 'prestador:create',
            'descricao' => __('messages.prestador_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 62,
            'nome' => 'prestador:update',
            'descricao' => __('messages.prestador_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 62,
            'nome' => 'prestador:delete',
            'descricao' => __('messages.prestador_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 49,
            'nome' => 'moeda:view',
            'descricao' => __('messages.moeda_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 49,
            'nome' => 'moeda:create',
            'descricao' => __('messages.moeda_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 49,
            'nome' => 'moeda:update',
            'descricao' => __('messages.moeda_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 49,
            'nome' => 'moeda:delete',
            'descricao' => __('messages.moeda_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 57,
            'nome' => 'pais:view',
            'descricao' => __('messages.pais_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 57,
            'nome' => 'pais:create',
            'descricao' => __('messages.pais_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 57,
            'nome' => 'pais:update',
            'descricao' => __('messages.pais_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 57,
            'nome' => 'pais:delete',
            'descricao' => __('messages.pais_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 29,
            'nome' => 'estado:view',
            'descricao' => __('messages.estado_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 29,
            'nome' => 'estado:create',
            'descricao' => __('messages.estado_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 29,
            'nome' => 'estado:update',
            'descricao' => __('messages.estado_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 29,
            'nome' => 'estado:delete',
            'descricao' => __('messages.estado_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 14,
            'nome' => 'cidade:view',
            'descricao' => __('messages.cidade_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 14,
            'nome' => 'cidade:create',
            'descricao' => __('messages.cidade_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 14,
            'nome' => 'cidade:update',
            'descricao' => __('messages.cidade_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 14,
            'nome' => 'cidade:delete',
            'descricao' => __('messages.cidade_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 5,
            'nome' => 'bairro:view',
            'descricao' => __('messages.bairro_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 5,
            'nome' => 'bairro:create',
            'descricao' => __('messages.bairro_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 5,
            'nome' => 'bairro:update',
            'descricao' => __('messages.bairro_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 5,
            'nome' => 'bairro:delete',
            'descricao' => __('messages.bairro_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 77,
            'nome' => 'zona:view',
            'descricao' => __('messages.zona_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 77,
            'nome' => 'zona:create',
            'descricao' => __('messages.zona_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 77,
            'nome' => 'zona:update',
            'descricao' => __('messages.zona_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 77,
            'nome' => 'zona:delete',
            'descricao' => __('messages.zona_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 45,
            'nome' => 'localizacao:view',
            'descricao' => __('messages.localizacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 45,
            'nome' => 'localizacao:create',
            'descricao' => __('messages.localizacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 45,
            'nome' => 'localizacao:update',
            'descricao' => __('messages.localizacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 45,
            'nome' => 'localizacao:delete',
            'descricao' => __('messages.localizacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 17,
            'nome' => 'comanda:view',
            'descricao' => __('messages.comanda_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 17,
            'nome' => 'comanda:create',
            'descricao' => __('messages.comanda_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 17,
            'nome' => 'comanda:update',
            'descricao' => __('messages.comanda_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 17,
            'nome' => 'comanda:delete',
            'descricao' => __('messages.comanda_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 76,
            'nome' => 'viagem:view',
            'descricao' => __('messages.viagem_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 76,
            'nome' => 'viagem:create',
            'descricao' => __('messages.viagem_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 76,
            'nome' => 'viagem:update',
            'descricao' => __('messages.viagem_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 76,
            'nome' => 'viagem:delete',
            'descricao' => __('messages.viagem_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 41,
            'nome' => 'integracao:view',
            'descricao' => __('messages.integracao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 41,
            'nome' => 'integracao:create',
            'descricao' => __('messages.integracao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 41,
            'nome' => 'integracao:update',
            'descricao' => __('messages.integracao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 41,
            'nome' => 'integracao:delete',
            'descricao' => __('messages.integracao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 2,
            'nome' => 'associacao:view',
            'descricao' => __('messages.associacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 2,
            'nome' => 'associacao:create',
            'descricao' => __('messages.associacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 2,
            'nome' => 'associacao:update',
            'descricao' => __('messages.associacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 2,
            'nome' => 'associacao:delete',
            'descricao' => __('messages.associacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:view',
            'descricao' => __('messages.pedido_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:create',
            'descricao' => __('messages.pedido_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:type:table',
            'descricao' => __('messages.pedido_type_table_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:type:card',
            'descricao' => __('messages.pedido_type_card_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:type:counter',
            'descricao' => __('messages.pedido_type_counter_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:type:delivery',
            'descricao' => __('messages.pedido_type_delivery_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:update',
            'descricao' => __('messages.pedido_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 59,
            'nome' => 'pedido:delete',
            'descricao' => __('messages.pedido_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 12,
            'nome' => 'categoria:view',
            'descricao' => __('messages.categoria_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 12,
            'nome' => 'categoria:create',
            'descricao' => __('messages.categoria_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 12,
            'nome' => 'categoria:update',
            'descricao' => __('messages.categoria_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 12,
            'nome' => 'categoria:delete',
            'descricao' => __('messages.categoria_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 75,
            'nome' => 'unidade:view',
            'descricao' => __('messages.unidade_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 75,
            'nome' => 'unidade:create',
            'descricao' => __('messages.unidade_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 75,
            'nome' => 'unidade:update',
            'descricao' => __('messages.unidade_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 75,
            'nome' => 'unidade:delete',
            'descricao' => __('messages.unidade_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 54,
            'nome' => 'origem:view',
            'descricao' => __('messages.origem_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 54,
            'nome' => 'origem:create',
            'descricao' => __('messages.origem_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 54,
            'nome' => 'origem:update',
            'descricao' => __('messages.origem_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 54,
            'nome' => 'origem:delete',
            'descricao' => __('messages.origem_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 53,
            'nome' => 'operacao:view',
            'descricao' => __('messages.operacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 53,
            'nome' => 'operacao:create',
            'descricao' => __('messages.operacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 53,
            'nome' => 'operacao:update',
            'descricao' => __('messages.operacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 53,
            'nome' => 'operacao:delete',
            'descricao' => __('messages.operacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 39,
            'nome' => 'imposto:view',
            'descricao' => __('messages.imposto_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 39,
            'nome' => 'imposto:create',
            'descricao' => __('messages.imposto_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 39,
            'nome' => 'imposto:update',
            'descricao' => __('messages.imposto_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 39,
            'nome' => 'imposto:delete',
            'descricao' => __('messages.imposto_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 74,
            'nome' => 'tributacao:view',
            'descricao' => __('messages.tributacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 74,
            'nome' => 'tributacao:create',
            'descricao' => __('messages.tributacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 74,
            'nome' => 'tributacao:update',
            'descricao' => __('messages.tributacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 74,
            'nome' => 'tributacao:delete',
            'descricao' => __('messages.tributacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 63,
            'nome' => 'produto:view',
            'descricao' => __('messages.produto_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 63,
            'nome' => 'produto:create',
            'descricao' => __('messages.produto_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 63,
            'nome' => 'produto:update',
            'descricao' => __('messages.produto_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 63,
            'nome' => 'produto:delete',
            'descricao' => __('messages.produto_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 69,
            'nome' => 'servico:view',
            'descricao' => __('messages.servico_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 69,
            'nome' => 'servico:create',
            'descricao' => __('messages.servico_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 69,
            'nome' => 'servico:update',
            'descricao' => __('messages.servico_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 69,
            'nome' => 'servico:delete',
            'descricao' => __('messages.servico_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 15,
            'nome' => 'classificacao:view',
            'descricao' => __('messages.classificacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 15,
            'nome' => 'classificacao:create',
            'descricao' => __('messages.classificacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 15,
            'nome' => 'classificacao:update',
            'descricao' => __('messages.classificacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 15,
            'nome' => 'classificacao:delete',
            'descricao' => __('messages.classificacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 21,
            'nome' => 'conta:view',
            'descricao' => __('messages.conta_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 21,
            'nome' => 'conta:create',
            'descricao' => __('messages.conta_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 21,
            'nome' => 'conta:update',
            'descricao' => __('messages.conta_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 21,
            'nome' => 'conta:delete',
            'descricao' => __('messages.conta_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 50,
            'nome' => 'movimentacao:view',
            'descricao' => __('messages.movimentacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 50,
            'nome' => 'movimentacao:create',
            'descricao' => __('messages.movimentacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 50,
            'nome' => 'movimentacao:update',
            'descricao' => __('messages.movimentacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 50,
            'nome' => 'movimentacao:delete',
            'descricao' => __('messages.movimentacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 23,
            'nome' => 'credito:view',
            'descricao' => __('messages.credito_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 23,
            'nome' => 'credito:create',
            'descricao' => __('messages.credito_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 23,
            'nome' => 'credito:update',
            'descricao' => __('messages.credito_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 23,
            'nome' => 'credito:delete',
            'descricao' => __('messages.credito_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 13,
            'nome' => 'cheque:view',
            'descricao' => __('messages.cheque_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 13,
            'nome' => 'cheque:create',
            'descricao' => __('messages.cheque_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 13,
            'nome' => 'cheque:update',
            'descricao' => __('messages.cheque_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 13,
            'nome' => 'cheque:delete',
            'descricao' => __('messages.cheque_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 56,
            'nome' => 'pagamento:view',
            'descricao' => __('messages.pagamento_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 56,
            'nome' => 'pagamento:create',
            'descricao' => __('messages.pagamento_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 56,
            'nome' => 'pagamento:update',
            'descricao' => __('messages.pagamento_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 56,
            'nome' => 'pagamento:delete',
            'descricao' => __('messages.pagamento_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 42,
            'nome' => 'item:view',
            'descricao' => __('messages.item_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 42,
            'nome' => 'item:create',
            'descricao' => __('messages.item_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 42,
            'nome' => 'item:update',
            'descricao' => __('messages.item_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 42,
            'nome' => 'item:delete',
            'descricao' => __('messages.item_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 48,
            'nome' => 'modulo:view',
            'descricao' => __('messages.modulo_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 48,
            'nome' => 'modulo:create',
            'descricao' => __('messages.modulo_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 48,
            'nome' => 'modulo:update',
            'descricao' => __('messages.modulo_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 48,
            'nome' => 'modulo:delete',
            'descricao' => __('messages.modulo_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 35,
            'nome' => 'funcionalidade:view',
            'descricao' => __('messages.funcionalidade_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 35,
            'nome' => 'funcionalidade:create',
            'descricao' => __('messages.funcionalidade_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 35,
            'nome' => 'funcionalidade:update',
            'descricao' => __('messages.funcionalidade_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 35,
            'nome' => 'funcionalidade:delete',
            'descricao' => __('messages.funcionalidade_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 60,
            'nome' => 'permissao:view',
            'descricao' => __('messages.permissao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 60,
            'nome' => 'permissao:create',
            'descricao' => __('messages.permissao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 60,
            'nome' => 'permissao:update',
            'descricao' => __('messages.permissao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 60,
            'nome' => 'permissao:delete',
            'descricao' => __('messages.permissao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 3,
            'nome' => 'auditoria:view',
            'descricao' => __('messages.auditoria_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 3,
            'nome' => 'auditoria:create',
            'descricao' => __('messages.auditoria_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 3,
            'nome' => 'auditoria:update',
            'descricao' => __('messages.auditoria_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 3,
            'nome' => 'auditoria:delete',
            'descricao' => __('messages.auditoria_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 18,
            'nome' => 'composicao:view',
            'descricao' => __('messages.composicao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 18,
            'nome' => 'composicao:create',
            'descricao' => __('messages.composicao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 18,
            'nome' => 'composicao:update',
            'descricao' => __('messages.composicao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 18,
            'nome' => 'composicao:delete',
            'descricao' => __('messages.composicao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 34,
            'nome' => 'fornecedor:view',
            'descricao' => __('messages.fornecedor_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 34,
            'nome' => 'fornecedor:create',
            'descricao' => __('messages.fornecedor_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 34,
            'nome' => 'fornecedor:update',
            'descricao' => __('messages.fornecedor_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 34,
            'nome' => 'fornecedor:delete',
            'descricao' => __('messages.fornecedor_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 44,
            'nome' => 'lista:view',
            'descricao' => __('messages.lista_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 44,
            'nome' => 'lista:create',
            'descricao' => __('messages.lista_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 44,
            'nome' => 'lista:update',
            'descricao' => __('messages.lista_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 44,
            'nome' => 'lista:delete',
            'descricao' => __('messages.lista_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 19,
            'nome' => 'compra:view',
            'descricao' => __('messages.compra_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 19,
            'nome' => 'compra:create',
            'descricao' => __('messages.compra_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 19,
            'nome' => 'compra:update',
            'descricao' => __('messages.compra_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 19,
            'nome' => 'compra:delete',
            'descricao' => __('messages.compra_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 67,
            'nome' => 'requisito:view',
            'descricao' => __('messages.requisito_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 67,
            'nome' => 'requisito:create',
            'descricao' => __('messages.requisito_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 67,
            'nome' => 'requisito:update',
            'descricao' => __('messages.requisito_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 67,
            'nome' => 'requisito:delete',
            'descricao' => __('messages.requisito_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 30,
            'nome' => 'estoque:view',
            'descricao' => __('messages.estoque_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 30,
            'nome' => 'estoque:create',
            'descricao' => __('messages.estoque_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 30,
            'nome' => 'estoque:update',
            'descricao' => __('messages.estoque_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 30,
            'nome' => 'estoque:delete',
            'descricao' => __('messages.estoque_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 37,
            'nome' => 'grupo:view',
            'descricao' => __('messages.grupo_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 37,
            'nome' => 'grupo:create',
            'descricao' => __('messages.grupo_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 37,
            'nome' => 'grupo:update',
            'descricao' => __('messages.grupo_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 37,
            'nome' => 'grupo:delete',
            'descricao' => __('messages.grupo_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 65,
            'nome' => 'propriedade:view',
            'descricao' => __('messages.propriedade_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 65,
            'nome' => 'propriedade:create',
            'descricao' => __('messages.propriedade_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 65,
            'nome' => 'propriedade:update',
            'descricao' => __('messages.propriedade_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 65,
            'nome' => 'propriedade:delete',
            'descricao' => __('messages.propriedade_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 55,
            'nome' => 'pacote:view',
            'descricao' => __('messages.pacote_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 55,
            'nome' => 'pacote:create',
            'descricao' => __('messages.pacote_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 55,
            'nome' => 'pacote:update',
            'descricao' => __('messages.pacote_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 55,
            'nome' => 'pacote:delete',
            'descricao' => __('messages.pacote_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 25,
            'nome' => 'dispositivo:view',
            'descricao' => __('messages.dispositivo_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 25,
            'nome' => 'dispositivo:create',
            'descricao' => __('messages.dispositivo_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 25,
            'nome' => 'dispositivo:update',
            'descricao' => __('messages.dispositivo_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 25,
            'nome' => 'dispositivo:delete',
            'descricao' => __('messages.dispositivo_delete_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 25,
            'nome' => 'dispositivo:validate',
            'descricao' => __('messages.dispositivo_validate_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 40,
            'nome' => 'impressora:view',
            'descricao' => __('messages.impressora_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 40,
            'nome' => 'impressora:create',
            'descricao' => __('messages.impressora_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 40,
            'nome' => 'impressora:update',
            'descricao' => __('messages.impressora_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 40,
            'nome' => 'impressora:delete',
            'descricao' => __('messages.impressora_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 64,
            'nome' => 'promocao:view',
            'descricao' => __('messages.promocao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 64,
            'nome' => 'promocao:create',
            'descricao' => __('messages.promocao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 64,
            'nome' => 'promocao:update',
            'descricao' => __('messages.promocao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 64,
            'nome' => 'promocao:delete',
            'descricao' => __('messages.promocao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 1,
            'nome' => 'acesso:view',
            'descricao' => __('messages.acesso_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 1,
            'nome' => 'acesso:create',
            'descricao' => __('messages.acesso_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 1,
            'nome' => 'acesso:update',
            'descricao' => __('messages.acesso_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 1,
            'nome' => 'acesso:delete',
            'descricao' => __('messages.acesso_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 11,
            'nome' => 'catalogo:view',
            'descricao' => __('messages.catalogo_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 11,
            'nome' => 'catalogo:create',
            'descricao' => __('messages.catalogo_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 11,
            'nome' => 'catalogo:update',
            'descricao' => __('messages.catalogo_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 11,
            'nome' => 'catalogo:delete',
            'descricao' => __('messages.catalogo_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 72,
            'nome' => 'sistema:view',
            'descricao' => __('messages.sistema_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 72,
            'nome' => 'sistema:create',
            'descricao' => __('messages.sistema_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 72,
            'nome' => 'sistema:update',
            'descricao' => __('messages.sistema_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 72,
            'nome' => 'sistema:delete',
            'descricao' => __('messages.sistema_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 68,
            'nome' => 'resumo:view',
            'descricao' => __('messages.resumo_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 68,
            'nome' => 'resumo:create',
            'descricao' => __('messages.resumo_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 68,
            'nome' => 'resumo:update',
            'descricao' => __('messages.resumo_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 68,
            'nome' => 'resumo:delete',
            'descricao' => __('messages.resumo_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 32,
            'nome' => 'formacao:view',
            'descricao' => __('messages.formacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 32,
            'nome' => 'formacao:create',
            'descricao' => __('messages.formacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 32,
            'nome' => 'formacao:update',
            'descricao' => __('messages.formacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 32,
            'nome' => 'formacao:delete',
            'descricao' => __('messages.formacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 28,
            'nome' => 'endereco:view',
            'descricao' => __('messages.endereco_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 28,
            'nome' => 'endereco:create',
            'descricao' => __('messages.endereco_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 28,
            'nome' => 'endereco:update',
            'descricao' => __('messages.endereco_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 28,
            'nome' => 'endereco:delete',
            'descricao' => __('messages.endereco_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 38,
            'nome' => 'horario:view',
            'descricao' => __('messages.horario_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 38,
            'nome' => 'horario:create',
            'descricao' => __('messages.horario_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 38,
            'nome' => 'horario:update',
            'descricao' => __('messages.horario_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 38,
            'nome' => 'horario:delete',
            'descricao' => __('messages.horario_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 58,
            'nome' => 'patrimonio:view',
            'descricao' => __('messages.patrimonio_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 58,
            'nome' => 'patrimonio:create',
            'descricao' => __('messages.patrimonio_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 58,
            'nome' => 'patrimonio:update',
            'descricao' => __('messages.patrimonio_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 58,
            'nome' => 'patrimonio:delete',
            'descricao' => __('messages.patrimonio_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 43,
            'nome' => 'juncao:view',
            'descricao' => __('messages.juncao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 43,
            'nome' => 'juncao:create',
            'descricao' => __('messages.juncao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 43,
            'nome' => 'juncao:update',
            'descricao' => __('messages.juncao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 43,
            'nome' => 'juncao:delete',
            'descricao' => __('messages.juncao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 66,
            'nome' => 'regime:view',
            'descricao' => __('messages.regime_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 66,
            'nome' => 'regime:create',
            'descricao' => __('messages.regime_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 66,
            'nome' => 'regime:update',
            'descricao' => __('messages.regime_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 66,
            'nome' => 'regime:delete',
            'descricao' => __('messages.regime_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 26,
            'nome' => 'emitente:view',
            'descricao' => __('messages.emitente_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 26,
            'nome' => 'emitente:create',
            'descricao' => __('messages.emitente_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 26,
            'nome' => 'emitente:update',
            'descricao' => __('messages.emitente_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 26,
            'nome' => 'emitente:delete',
            'descricao' => __('messages.emitente_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 51,
            'nome' => 'nota:view',
            'descricao' => __('messages.nota_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 51,
            'nome' => 'nota:create',
            'descricao' => __('messages.nota_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 51,
            'nome' => 'nota:update',
            'descricao' => __('messages.nota_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 51,
            'nome' => 'nota:delete',
            'descricao' => __('messages.nota_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 31,
            'nome' => 'evento:view',
            'descricao' => __('messages.evento_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 31,
            'nome' => 'evento:create',
            'descricao' => __('messages.evento_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 31,
            'nome' => 'evento:update',
            'descricao' => __('messages.evento_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 31,
            'nome' => 'evento:delete',
            'descricao' => __('messages.evento_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 27,
            'nome' => 'empresa:view',
            'descricao' => __('messages.empresa_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 27,
            'nome' => 'empresa:create',
            'descricao' => __('messages.empresa_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 27,
            'nome' => 'empresa:update',
            'descricao' => __('messages.empresa_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 27,
            'nome' => 'empresa:delete',
            'descricao' => __('messages.empresa_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 61,
            'nome' => 'pontuacao:view',
            'descricao' => __('messages.pontuacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 61,
            'nome' => 'pontuacao:create',
            'descricao' => __('messages.pontuacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 61,
            'nome' => 'pontuacao:update',
            'descricao' => __('messages.pontuacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 61,
            'nome' => 'pontuacao:delete',
            'descricao' => __('messages.pontuacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 73,
            'nome' => 'telefone:view',
            'descricao' => __('messages.telefone_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 73,
            'nome' => 'telefone:create',
            'descricao' => __('messages.telefone_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 73,
            'nome' => 'telefone:update',
            'descricao' => __('messages.telefone_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 73,
            'nome' => 'telefone:delete',
            'descricao' => __('messages.telefone_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 52,
            'nome' => 'observacao:view',
            'descricao' => __('messages.observacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 52,
            'nome' => 'observacao:create',
            'descricao' => __('messages.observacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 52,
            'nome' => 'observacao:update',
            'descricao' => __('messages.observacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 52,
            'nome' => 'observacao:delete',
            'descricao' => __('messages.observacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 24,
            'nome' => 'cupom:view',
            'descricao' => __('messages.cupom_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 24,
            'nome' => 'cupom:create',
            'descricao' => __('messages.cupom_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 24,
            'nome' => 'cupom:update',
            'descricao' => __('messages.cupom_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 24,
            'nome' => 'cupom:delete',
            'descricao' => __('messages.cupom_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 47,
            'nome' => 'metrica:view',
            'descricao' => __('messages.metrica_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 47,
            'nome' => 'metrica:create',
            'descricao' => __('messages.metrica_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 47,
            'nome' => 'metrica:update',
            'descricao' => __('messages.metrica_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 47,
            'nome' => 'metrica:delete',
            'descricao' => __('messages.metrica_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 4,
            'nome' => 'avaliacao:view',
            'descricao' => __('messages.avaliacao_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 4,
            'nome' => 'avaliacao:create',
            'descricao' => __('messages.avaliacao_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 4,
            'nome' => 'avaliacao:update',
            'descricao' => __('messages.avaliacao_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 4,
            'nome' => 'avaliacao:delete',
            'descricao' => __('messages.avaliacao_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 22,
            'nome' => 'cozinha:view',
            'descricao' => __('messages.cozinha_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 22,
            'nome' => 'cozinha:create',
            'descricao' => __('messages.cozinha_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 22,
            'nome' => 'cozinha:update',
            'descricao' => __('messages.cozinha_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 22,
            'nome' => 'cozinha:delete',
            'descricao' => __('messages.cozinha_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 8,
            'nome' => 'cardapio:view',
            'descricao' => __('messages.cardapio_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 8,
            'nome' => 'cardapio:create',
            'descricao' => __('messages.cardapio_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 8,
            'nome' => 'cardapio:update',
            'descricao' => __('messages.cardapio_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 8,
            'nome' => 'cardapio:delete',
            'descricao' => __('messages.cardapio_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 20,
            'nome' => 'contagem:view',
            'descricao' => __('messages.contagem_view_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 20,
            'nome' => 'contagem:create',
            'descricao' => __('messages.contagem_create_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 20,
            'nome' => 'contagem:update',
            'descricao' => __('messages.contagem_update_permition'),
        ]))->save();
        (new Permissao([
            'funcionalidade_id' => 20,
            'nome' => 'contagem:delete',
            'descricao' => __('messages.contagem_delete_permition'),
        ]))->save();

        (new Permissao([
            'funcionalidade_id' => 9,
            'nome' => 'saldo:view',
            'descricao' => __('messages.saldo_view_permition'),
        ]))->save();
    }
}

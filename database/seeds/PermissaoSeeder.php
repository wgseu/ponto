<?php

use App\Models\Permissao;
use Illuminate\Database\Seeder;

class PermissaoSeeder extends Seeder
{
    /**
     * Salva permissões agrupadas por funcionalidade
     *
     * @param int $funcionalidade_id
     * @param array $permissions
     * @return void
     */
    public function group($funcionalidade_id, $permissions)
    {
        foreach ($permissions as $permission) {
            list($name, $description) = $permission;
            (new Permissao([
                'funcionalidade_id' => $funcionalidade_id,
                'nome' => $name,
                'descricao' => __("messages.$description"),
            ]))->save();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->group(1, [
            ['acesso:view', 'acesso_view_permition'],
            ['acesso:create', 'acesso_create_permition'],
            ['acesso:delete', 'acesso_delete_permition'],
        ]);
        $this->group(2, [
            ['associacao:view', 'associacao_view_permition'],
            ['associacao:update', 'associacao_update_permition'],
        ]);
        $this->group(3, [
            ['auditoria:view', 'auditoria_view_permition'],
        ]);
        $this->group(4, [
            ['avaliacao:view', 'avaliacao_view_permition'],
            ['avaliacao:update', 'avaliacao_update_permition'],
        ]);
        $this->group(5, [
            ['bairro:view', 'bairro_view_permition'],
            ['bairro:create', 'bairro_create_permition'],
            ['bairro:update', 'bairro_update_permition'],
            ['bairro:delete', 'bairro_delete_permition'],
        ]);
        $this->group(6, [
            ['banco:view', 'banco_view_permition'],
            ['banco:create', 'banco_create_permition'],
            ['banco:update', 'banco_update_permition'],
            ['banco:delete', 'banco_delete_permition'],
        ]);
        $this->group(7, [
            ['caixa:view', 'caixa_view_permition'],
            ['caixa:create', 'caixa_create_permition'],
            ['caixa:update', 'caixa_update_permition'],
            ['caixa:delete', 'caixa_delete_permition'],
            ['caixa:reopen', 'caixa_reopen_permition'],
        ]);
        $this->group(8, [
            ['cardapio:create', 'cardapio_create_permition'],
            ['cardapio:update', 'cardapio_update_permition'],
            ['cardapio:delete', 'cardapio_delete_permition'],
        ]);
        $this->group(9, [
            ['carteira:view', 'carteira_view_permition'],
            ['carteira:create', 'carteira_create_permition'],
            ['carteira:update', 'carteira_update_permition'],
            ['carteira:delete', 'carteira_delete_permition'],

            ['saldo:view', 'saldo_view_permition'],
        ]);
        $this->group(10, [
            ['cartao:view', 'cartao_view_permition'],
            ['cartao:create', 'cartao_create_permition'],
            ['cartao:update', 'cartao_update_permition'],
            ['cartao:delete', 'cartao_delete_permition'],
        ]);
        $this->group(11, [
            ['catalogo:view', 'catalogo_view_permition'],
            ['catalogo:create', 'catalogo_create_permition'],
            ['catalogo:update', 'catalogo_update_permition'],
            ['catalogo:delete', 'catalogo_delete_permition'],
        ]);
        $this->group(12, [
            ['categoria:view', 'categoria_view_permition'],
            ['categoria:create', 'categoria_create_permition'],
            ['categoria:update', 'categoria_update_permition'],
            ['categoria:delete', 'categoria_delete_permition'],
        ]);
        $this->group(13, [
            ['cheque:view', 'cheque_view_permition'],
            ['cheque:update', 'cheque_update_permition'],
        ]);
        $this->group(14, [
            ['cidade:view', 'cidade_view_permition'],
            ['cidade:create', 'cidade_create_permition'],
            ['cidade:update', 'cidade_update_permition'],
            ['cidade:delete', 'cidade_delete_permition'],
        ]);
        $this->group(15, [
            ['classificacao:view', 'classificacao_view_permition'],
            ['classificacao:create', 'classificacao_create_permition'],
            ['classificacao:update', 'classificacao_update_permition'],
            ['classificacao:delete', 'classificacao_delete_permition'],
        ]);
        $this->group(16, [
            ['cliente:view', 'cliente_view_permition'],
            ['cliente:create', 'cliente_create_permition'],
            ['cliente:update', 'cliente_update_permition'],
            ['cliente:delete', 'cliente_delete_permition'],
        ]);
        $this->group(17, [
            ['comanda:view', 'comanda_view_permition'],
            ['comanda:create', 'comanda_create_permition'],
            ['comanda:update', 'comanda_update_permition'],
            ['comanda:delete', 'comanda_delete_permition'],
        ]);
        $this->group(18, [
            ['composicao:create', 'composicao_create_permition'],
            ['composicao:update', 'composicao_update_permition'],
            ['composicao:delete', 'composicao_delete_permition'],
        ]);
        $this->group(19, [
            ['compra:view', 'compra_view_permition'],
            ['compra:create', 'compra_create_permition'],
            ['compra:update', 'compra_update_permition'],
            ['compra:delete', 'compra_delete_permition'],
        ]);
        $this->group(20, [
            ['contagem:view', 'contagem_view_permition'],
        ]);
        $this->group(21, [
            ['conta:view', 'conta_view_permition'],
            ['conta:create', 'conta_create_permition'],
            ['conta:update', 'conta_update_permition'],
            ['conta:delete', 'conta_delete_permition'],
        ]);
        $this->group(22, [
            ['cozinha:view', 'cozinha_view_permition'],
            ['cozinha:create', 'cozinha_create_permition'],
            ['cozinha:update', 'cozinha_update_permition'],
            ['cozinha:delete', 'cozinha_delete_permition'],
        ]);
        $this->group(23, [
            ['credito:view', 'credito_view_permition'],
            ['credito:create', 'credito_create_permition'],
            ['credito:update', 'credito_update_permition'],
            ['credito:cancel', 'credito_cancel_permition'],
        ]);
        $this->group(24, [
            ['cupom:view', 'cupom_view_permition'],
            ['cupom:create', 'cupom_create_permition'],
            ['cupom:update', 'cupom_update_permition'],
            ['cupom:delete', 'cupom_delete_permition'],
        ]);
        $this->group(25, [
            ['dispositivo:view', 'dispositivo_view_permition'],
            ['dispositivo:create', 'dispositivo_create_permition'],
            ['dispositivo:update', 'dispositivo_update_permition'],
            ['dispositivo:delete', 'dispositivo_delete_permition'],
            ['dispositivo:validate', 'dispositivo_validate_permition'],
        ]);
        $this->group(26, [
            ['emitente:view', 'emitente_view_permition'],
            ['emitente:update', 'emitente_update_permition'],
        ]);
        $this->group(27, [
            ['empresa:view', 'empresa_view_permition'],
            ['empresa:update', 'empresa_update_permition'],
        ]);
        $this->group(29, [
            ['estado:view', 'estado_view_permition'],
            ['estado:create', 'estado_create_permition'],
            ['estado:update', 'estado_update_permition'],
            ['estado:delete', 'estado_delete_permition'],
        ]);
        $this->group(30, [
            ['estoque:view', 'estoque_view_permition'],
            ['estoque:create', 'estoque_create_permition'],
            ['estoque:update', 'estoque_update_permition'],
            ['estoque:cancel', 'estoque_cancel_permition'],
        ]);
        $this->group(31, [
            ['evento:view', 'evento_view_permition'],
        ]);
        $this->group(33, [
            ['forma:view', 'forma_view_permition'],
            ['forma:create', 'forma_create_permition'],
            ['forma:update', 'forma_update_permition'],
            ['forma:delete', 'forma_delete_permition'],
        ]);
        $this->group(36, [
            ['funcao:view', 'funcao_view_permition'],
            ['funcao:create', 'funcao_create_permition'],
            ['funcao:update', 'funcao_update_permition'],
            ['funcao:delete', 'funcao_delete_permition'],
        ]);
        $this->group(37, [
            ['grupo:create', 'grupo_create_permition'],
            ['grupo:update', 'grupo_update_permition'],
            ['grupo:delete', 'grupo_delete_permition'],
        ]);
        $this->group(38, [
            ['horario:view', 'horario_view_permition'],
            ['horario:create', 'horario_create_permition'],
            ['horario:update', 'horario_update_permition'],
            ['horario:delete', 'horario_delete_permition'],
        ]);
        $this->group(39, [
            ['imposto:view', 'imposto_view_permition'],
            ['imposto:create', 'imposto_create_permition'],
            ['imposto:update', 'imposto_update_permition'],
            ['imposto:delete', 'imposto_delete_permition'],
        ]);
        $this->group(40, [
            ['impressora:view', 'impressora_view_permition'],
            ['impressora:create', 'impressora_create_permition'],
            ['impressora:update', 'impressora_update_permition'],
            ['impressora:delete', 'impressora_delete_permition'],
        ]);
        $this->group(41, [
            ['integracao:view', 'integracao_view_permition'],
            ['integracao:update', 'integracao_update_permition'],
        ]);
        $this->group(42, [
            ['item:view', 'item_view_permition'],
            ['item:prepare', 'item_prepare_permition'],
            ['item:cancel', 'item_cancel_permition'],
        ]);
        $this->group(43, [
            ['juncao:view', 'juncao_view_permition'],
            ['juncao:create', 'juncao_create_permition'],
            ['juncao:update', 'juncao_update_permition'],
        ]);
        $this->group(44, [
            ['lista:view', 'lista_view_permition'],
            ['lista:create', 'lista_create_permition'],
            ['lista:update', 'lista_update_permition'],
            ['lista:delete', 'lista_delete_permition'],
        ]);
        $this->group(45, [
            ['localizacao:create', 'localizacao_create_permition'],
            ['localizacao:update', 'localizacao_update_permition'],
            ['localizacao:delete', 'localizacao_delete_permition'],
        ]);
        $this->group(46, [
            ['mesa:view', 'mesa_view_permition'],
            ['mesa:create', 'mesa_create_permition'],
            ['mesa:update', 'mesa_update_permition'],
            ['mesa:delete', 'mesa_delete_permition'],
        ]);
        $this->group(47, [
            ['metrica:view', 'metrica_view_permition'],
            ['metrica:create', 'metrica_create_permition'],
            ['metrica:update', 'metrica_update_permition'],
            ['metrica:delete', 'metrica_delete_permition'],
        ]);
        $this->group(48, [
            ['modulo:view', 'modulo_view_permition'],
            ['modulo:update', 'modulo_update_permition'],
        ]);
        $this->group(49, [
            ['moeda:view', 'moeda_view_permition'],
            ['moeda:create', 'moeda_create_permition'],
            ['moeda:update', 'moeda_update_permition'],
            ['moeda:delete', 'moeda_delete_permition'],
        ]);
        $this->group(50, [
            ['movimentacao:view', 'movimentacao_view_permition'],
            ['movimentacao:create', 'movimentacao_create_permition'],
            ['movimentacao:update', 'movimentacao_update_permition'],
        ]);
        $this->group(51, [
            ['nota:view', 'nota_view_permition'],
            ['nota:create', 'nota_create_permition'],
            ['nota:update', 'nota_update_permition'],
            ['nota:cancel', 'nota_cancel_permition'],
        ]);
        $this->group(53, [
            ['operacao:view', 'operacao_view_permition'],
            ['operacao:create', 'operacao_create_permition'],
            ['operacao:update', 'operacao_update_permition'],
            ['operacao:delete', 'operacao_delete_permition'],
        ]);
        $this->group(54, [
            ['origem:view', 'origem_view_permition'],
            ['origem:create', 'origem_create_permition'],
            ['origem:update', 'origem_update_permition'],
            ['origem:delete', 'origem_delete_permition'],
        ]);
        $this->group(55, [
            ['pacote:create', 'pacote_create_permition'],
            ['pacote:update', 'pacote_update_permition'],
            ['pacote:delete', 'pacote_delete_permition'],
        ]);
        $this->group(56, [
            ['pagamento:view', 'pagamento_view_permition'],
            ['pagamento:update', 'pagamento_update_permition'],
        ]);
        $this->group(57, [
            ['pais:view', 'pais_view_permition'],
            ['pais:create', 'pais_create_permition'],
            ['pais:update', 'pais_update_permition'],
            ['pais:delete', 'pais_delete_permition'],
        ]);
        $this->group(59, [
            ['pedido:view', 'pedido_view_permition'],
            ['pedido:create', 'pedido_create_permition'],
            ['pedido:type:table', 'pedido_type_table_permition'],
            ['pedido:type:card', 'pedido_type_card_permition'],
            ['pedido:type:counter', 'pedido_type_counter_permition'],
            ['pedido:type:delivery', 'pedido_type_delivery_permition'],
            ['pedido:update', 'pedido_update_permition'],
            ['pedido:cancel', 'pedido_cancel_permition'],
        ]);
        $this->group(62, [
            ['prestador:view', 'prestador_view_permition'],
            ['prestador:create', 'prestador_create_permition'],
            ['prestador:update', 'prestador_update_permition'],
            ['prestador:delete', 'prestador_delete_permition'],
        ]);
        $this->group(63, [
            ['produto:view', 'produto_view_permition'],
            ['produto:create', 'produto_create_permition'],
            ['produto:update', 'produto_update_permition'],
            ['produto:delete', 'produto_delete_permition'],
        ]);
        $this->group(64, [
            ['promocao:create', 'promocao_create_permition'],
            ['promocao:update', 'promocao_update_permition'],
            ['promocao:delete', 'promocao_delete_permition'],
        ]);
        $this->group(65, [
            ['propriedade:create', 'propriedade_create_permition'],
            ['propriedade:update', 'propriedade_update_permition'],
            ['propriedade:delete', 'propriedade_delete_permition'],
        ]);
        $this->group(66, [
            ['regime:view', 'regime_view_permition'],
            ['regime:create', 'regime_create_permition'],
            ['regime:update', 'regime_update_permition'],
            ['regime:delete', 'regime_delete_permition'],
        ]);
        $this->group(67, [
            ['requisito:view', 'requisito_view_permition'],
            ['requisito:create', 'requisito_create_permition'],
            ['requisito:update', 'requisito_update_permition'],
            ['requisito:delete', 'requisito_delete_permition'],
        ]);
        $this->group(68, [
            ['resumo:view', 'resumo_view_permition'],
            ['resumo:create', 'resumo_create_permition'],
            ['resumo:update', 'resumo_update_permition'],
        ]);
        $this->group(69, [
            ['servico:view', 'servico_view_permition'],
            ['servico:create', 'servico_create_permition'],
            ['servico:update', 'servico_update_permition'],
            ['servico:delete', 'servico_delete_permition'],
        ]);
        $this->group(70, [
            ['sessao:view', 'sessao_view_permition'],
        ]);
        $this->group(71, [
            ['setor:view', 'setor_view_permition'],
            ['setor:create', 'setor_create_permition'],
            ['setor:update', 'setor_update_permition'],
            ['setor:delete', 'setor_delete_permition'],
        ]);
        $this->group(72, [
            ['sistema:view', 'sistema_view_permition'],
            ['sistema:update', 'sistema_update_permition'],
        ]);
        $this->group(74, [
            ['tributacao:view', 'tributacao_view_permition'],
            ['tributacao:create', 'tributacao_create_permition'],
            ['tributacao:update', 'tributacao_update_permition'],
            ['tributacao:delete', 'tributacao_delete_permition'],
        ]);
        $this->group(75, [
            ['unidade:view', 'unidade_view_permition'],
            ['unidade:create', 'unidade_create_permition'],
            ['unidade:update', 'unidade_update_permition'],
            ['unidade:delete', 'unidade_delete_permition'],
        ]);
        $this->group(76, [
            ['viagem:view', 'viagem_view_permition'],
            ['viagem:create', 'viagem_create_permition'],
            ['viagem:update', 'viagem_update_permition'],
            ['viagem:delete', 'viagem_delete_permition'],
        ]);
        $this->group(77, [
            ['zona:view', 'zona_view_permition'],
            ['zona:create', 'zona_create_permition'],
            ['zona:update', 'zona_update_permition'],
            ['zona:delete', 'zona_delete_permition'],
        ]);
    }
}

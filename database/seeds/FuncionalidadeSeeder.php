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
        (new Funcionalidade([
            'id' => 71,
            'modulo_id' => null,
            'nome' => __('messages.setor_functionality_name'),
            'descricao' => __('messages.setor_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 46,
            'modulo_id' => 1,
            'nome' => __('messages.mesa_functionality_name'),
            'descricao' => __('messages.mesa_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 70,
            'modulo_id' => null,
            'nome' => __('messages.sessao_functionality_name'),
            'descricao' => __('messages.sessao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 6,
            'modulo_id' => null,
            'nome' => __('messages.banco_functionality_name'),
            'descricao' => __('messages.banco_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 9,
            'modulo_id' => null,
            'nome' => __('messages.carteira_functionality_name'),
            'descricao' => __('messages.carteira_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 7,
            'modulo_id' => null,
            'nome' => __('messages.caixa_functionality_name'),
            'descricao' => __('messages.caixa_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 33,
            'modulo_id' => null,
            'nome' => __('messages.forma_functionality_name'),
            'descricao' => __('messages.forma_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 10,
            'modulo_id' => null,
            'nome' => __('messages.cartao_functionality_name'),
            'descricao' => __('messages.cartao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 36,
            'modulo_id' => null,
            'nome' => __('messages.funcao_functionality_name'),
            'descricao' => __('messages.funcao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 16,
            'modulo_id' => null,
            'nome' => __('messages.cliente_functionality_name'),
            'descricao' => __('messages.cliente_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 62,
            'modulo_id' => null,
            'nome' => __('messages.prestador_functionality_name'),
            'descricao' => __('messages.prestador_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 49,
            'modulo_id' => null,
            'nome' => __('messages.moeda_functionality_name'),
            'descricao' => __('messages.moeda_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 57,
            'modulo_id' => null,
            'nome' => __('messages.pais_functionality_name'),
            'descricao' => __('messages.pais_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 29,
            'modulo_id' => null,
            'nome' => __('messages.estado_functionality_name'),
            'descricao' => __('messages.estado_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 14,
            'modulo_id' => null,
            'nome' => __('messages.cidade_functionality_name'),
            'descricao' => __('messages.cidade_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 5,
            'modulo_id' => null,
            'nome' => __('messages.bairro_functionality_name'),
            'descricao' => __('messages.bairro_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 77,
            'modulo_id' => null,
            'nome' => __('messages.zona_functionality_name'),
            'descricao' => __('messages.zona_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 45,
            'modulo_id' => null,
            'nome' => __('messages.localizacao_functionality_name'),
            'descricao' => __('messages.localizacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 17,
            'modulo_id' => 2,
            'nome' => __('messages.comanda_functionality_name'),
            'descricao' => __('messages.comanda_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 76,
            'modulo_id' => null,
            'nome' => __('messages.viagem_functionality_name'),
            'descricao' => __('messages.viagem_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 41,
            'modulo_id' => null,
            'nome' => __('messages.integracao_functionality_name'),
            'descricao' => __('messages.integracao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 2,
            'modulo_id' => 5,
            'nome' => __('messages.associacao_functionality_name'),
            'descricao' => __('messages.associacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 59,
            'modulo_id' => null,
            'nome' => __('messages.pedido_functionality_name'),
            'descricao' => __('messages.pedido_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 12,
            'modulo_id' => null,
            'nome' => __('messages.categoria_functionality_name'),
            'descricao' => __('messages.categoria_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 75,
            'modulo_id' => null,
            'nome' => __('messages.unidade_functionality_name'),
            'descricao' => __('messages.unidade_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 54,
            'modulo_id' => 7,
            'nome' => __('messages.origem_functionality_name'),
            'descricao' => __('messages.origem_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 53,
            'modulo_id' => 7,
            'nome' => __('messages.operacao_functionality_name'),
            'descricao' => __('messages.operacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 39,
            'modulo_id' => 7,
            'nome' => __('messages.imposto_functionality_name'),
            'descricao' => __('messages.imposto_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 74,
            'modulo_id' => 7,
            'nome' => __('messages.tributacao_functionality_name'),
            'descricao' => __('messages.tributacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 63,
            'modulo_id' => null,
            'nome' => __('messages.produto_functionality_name'),
            'descricao' => __('messages.produto_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 69,
            'modulo_id' => null,
            'nome' => __('messages.servico_functionality_name'),
            'descricao' => __('messages.servico_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 15,
            'modulo_id' => null,
            'nome' => __('messages.classificacao_functionality_name'),
            'descricao' => __('messages.classificacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 21,
            'modulo_id' => null,
            'nome' => __('messages.conta_functionality_name'),
            'descricao' => __('messages.conta_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 50,
            'modulo_id' => null,
            'nome' => __('messages.movimentacao_functionality_name'),
            'descricao' => __('messages.movimentacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 23,
            'modulo_id' => null,
            'nome' => __('messages.credito_functionality_name'),
            'descricao' => __('messages.credito_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 13,
            'modulo_id' => null,
            'nome' => __('messages.cheque_functionality_name'),
            'descricao' => __('messages.cheque_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 56,
            'modulo_id' => null,
            'nome' => __('messages.pagamento_functionality_name'),
            'descricao' => __('messages.pagamento_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 42,
            'modulo_id' => null,
            'nome' => __('messages.item_functionality_name'),
            'descricao' => __('messages.item_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 48,
            'modulo_id' => null,
            'nome' => __('messages.modulo_functionality_name'),
            'descricao' => __('messages.modulo_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 35,
            'modulo_id' => null,
            'nome' => __('messages.funcionalidade_functionality_name'),
            'descricao' => __('messages.funcionalidade_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 60,
            'modulo_id' => null,
            'nome' => __('messages.permissao_functionality_name'),
            'descricao' => __('messages.permissao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 3,
            'modulo_id' => null,
            'nome' => __('messages.auditoria_functionality_name'),
            'descricao' => __('messages.auditoria_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 18,
            'modulo_id' => null,
            'nome' => __('messages.composicao_functionality_name'),
            'descricao' => __('messages.composicao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 34,
            'modulo_id' => null,
            'nome' => __('messages.fornecedor_functionality_name'),
            'descricao' => __('messages.fornecedor_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 44,
            'modulo_id' => null,
            'nome' => __('messages.lista_functionality_name'),
            'descricao' => __('messages.lista_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 19,
            'modulo_id' => null,
            'nome' => __('messages.compra_functionality_name'),
            'descricao' => __('messages.compra_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 67,
            'modulo_id' => null,
            'nome' => __('messages.requisito_functionality_name'),
            'descricao' => __('messages.requisito_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 30,
            'modulo_id' => null,
            'nome' => __('messages.estoque_functionality_name'),
            'descricao' => __('messages.estoque_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 37,
            'modulo_id' => null,
            'nome' => __('messages.grupo_functionality_name'),
            'descricao' => __('messages.grupo_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 65,
            'modulo_id' => null,
            'nome' => __('messages.propriedade_functionality_name'),
            'descricao' => __('messages.propriedade_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 55,
            'modulo_id' => null,
            'nome' => __('messages.pacote_functionality_name'),
            'descricao' => __('messages.pacote_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 25,
            'modulo_id' => null,
            'nome' => __('messages.dispositivo_functionality_name'),
            'descricao' => __('messages.dispositivo_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 40,
            'modulo_id' => null,
            'nome' => __('messages.impressora_functionality_name'),
            'descricao' => __('messages.impressora_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 64,
            'modulo_id' => null,
            'nome' => __('messages.promocao_functionality_name'),
            'descricao' => __('messages.promocao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 1,
            'modulo_id' => null,
            'nome' => __('messages.acesso_functionality_name'),
            'descricao' => __('messages.acesso_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 11,
            'modulo_id' => null,
            'nome' => __('messages.catalogo_functionality_name'),
            'descricao' => __('messages.catalogo_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 72,
            'modulo_id' => null,
            'nome' => __('messages.sistema_functionality_name'),
            'descricao' => __('messages.sistema_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 68,
            'modulo_id' => null,
            'nome' => __('messages.resumo_functionality_name'),
            'descricao' => __('messages.resumo_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 32,
            'modulo_id' => null,
            'nome' => __('messages.formacao_functionality_name'),
            'descricao' => __('messages.formacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 38,
            'modulo_id' => null,
            'nome' => __('messages.horario_functionality_name'),
            'descricao' => __('messages.horario_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 43,
            'modulo_id' => 1,
            'nome' => __('messages.juncao_functionality_name'),
            'descricao' => __('messages.juncao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 66,
            'modulo_id' => 7,
            'nome' => __('messages.regime_functionality_name'),
            'descricao' => __('messages.regime_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 26,
            'modulo_id' => 7,
            'nome' => __('messages.emitente_functionality_name'),
            'descricao' => __('messages.emitente_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 51,
            'modulo_id' => 7,
            'nome' => __('messages.nota_functionality_name'),
            'descricao' => __('messages.nota_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 31,
            'modulo_id' => 7,
            'nome' => __('messages.evento_functionality_name'),
            'descricao' => __('messages.evento_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 27,
            'modulo_id' => null,
            'nome' => __('messages.empresa_functionality_name'),
            'descricao' => __('messages.empresa_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 61,
            'modulo_id' => null,
            'nome' => __('messages.pontuacao_functionality_name'),
            'descricao' => __('messages.pontuacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 73,
            'modulo_id' => null,
            'nome' => __('messages.telefone_functionality_name'),
            'descricao' => __('messages.telefone_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 52,
            'modulo_id' => null,
            'nome' => __('messages.observacao_functionality_name'),
            'descricao' => __('messages.observacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 24,
            'modulo_id' => null,
            'nome' => __('messages.cupom_functionality_name'),
            'descricao' => __('messages.cupom_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 47,
            'modulo_id' => null,
            'nome' => __('messages.metrica_functionality_name'),
            'descricao' => __('messages.metrica_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 4,
            'modulo_id' => 5,
            'nome' => __('messages.avaliacao_functionality_name'),
            'descricao' => __('messages.avaliacao_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 22,
            'modulo_id' => null,
            'nome' => __('messages.cozinha_functionality_name'),
            'descricao' => __('messages.cozinha_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 8,
            'modulo_id' => null,
            'nome' => __('messages.cardapio_functionality_name'),
            'descricao' => __('messages.cardapio_functionality_description'),
        ]))->save();
        (new Funcionalidade([
            'id' => 20,
            'modulo_id' => null,
            'nome' => __('messages.contagem_functionality_name'),
            'descricao' => __('messages.contagem_functionality_description'),
        ]))->save();
    }
}

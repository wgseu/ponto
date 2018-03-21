<?php

class InstantiationTest extends \PHPUnit_Framework_TestCase
{
    public function testOldClasses()
    {
        $mesa = new \ZMesa();
        $sessao = new \ZSessao();
        $caixa = new \ZCaixa();
        $forma_pagto = new \ZFormaPagto();
        $cartao = new \ZCartao();
        $funcao = new \ZFuncao();
        $cliente = new \ZCliente();
        $funcionario = new \ZFuncionario();
        $movimentacao = new \ZMovimentacao();
        $pedido = new \ZPedido();
        $categoria = new \ZCategoria();
        $unidade = new \ZUnidade();
        $setor = new \ZSetor();
        $origem = new \ZOrigem();
        $operacao = new \ZOperacao();
        $imposto = new \ZImposto();
        $tributacao = new \ZTributacao();
        $produto = new \ZProduto();
        $servico = new \ZServico();
        $produto_pedido = new \ZProdutoPedido();
        $cheque = new \ZCheque();
        $classificacao = new \ZClassificacao();
        $conta = new \ZConta();
        $credito = new \ZCredito();
        $pagamento = new \ZPagamento();
        $auditoria = new \ZAuditoria();
        $folha_cheque = new \ZFolhaCheque();
        $composicao = new \ZComposicao();
        $fornecedor = new \ZFornecedor();
        $estoque = new \ZEstoque();
        $grupo = new \ZGrupo();
        $propriedade = new \ZPropriedade();
        $dispositivo = new \ZDispositivo();
        $funcionalidade = new \ZFuncionalidade();
        $permissao = new \ZPermissao();
        $acesso = new \ZAcesso();
        $sistema = new \ZSistema();
        $formacao = new \ZFormacao();
        $modulo = new \ZModulo();
        $patrimonio = new \ZPatrimonio();
        $pagina = new \ZPagina();
        $regime = new \ZRegime();
        $emitente = new \ZEmitente();
        $nota = new \ZNota();
        $evento = new \ZEvento();
    }
}

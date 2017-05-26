<?php

class InstantiationTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testClasses()
    {
        $comanda = new \MZ\Sale\Comanda();
    }

    public function testOldClasses()
    {
        $mesa = new \ZMesa();
        $sessao = new \ZSessao();
        $caixa = new \ZCaixa();
        $banco = new \ZBanco();
        $carteira = new \ZCarteira();
        $forma_pagto = new \ZFormaPagto();
        $cartao = new \ZCartao();
        $funcao = new \ZFuncao();
        $cliente = new \ZCliente();
        $funcionario = new \ZFuncionario();
        $moeda = new \ZMoeda();
        $pais = new \ZPais();
        $estado = new \ZEstado();
        $cidade = new \ZCidade();
        $bairro = new \ZBairro();
        $localizacao = new \ZLocalizacao();
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
        $pacote = new \ZPacote();
        $dispositivo = new \ZDispositivo();
        $impressora = new \ZImpressora();
        $promocao = new \ZPromocao();
        $funcionalidade = new \ZFuncionalidade();
        $permissao = new \ZPermissao();
        $acesso = new \ZAcesso();
        $produto_fornecedor = new \ZProdutoFornecedor();
        $sistema = new \ZSistema();
        $informacao = new \ZInformacao();
        $resumo = new \ZResumo();
        $formacao = new \ZFormacao();
        $consumacao = new \ZConsumacao();
        $lista_compra = new \ZListaCompra();
        $modulo = new \ZModulo();
        $lista_produto = new \ZListaProduto();
        $endereco = new \ZEndereco();
        $horario = new \ZHorario();
        $valor_nutricional = new \ZValorNutricional();
        $transferencia = new \ZTransferencia();
        $patrimonio = new \ZPatrimonio();
        $pagina = new \ZPagina();
        $juncao = new \ZJuncao();
        $regime = new \ZRegime();
        $emitente = new \ZEmitente();
        $nota = new \ZNota();
        $evento = new \ZEvento();
    }
}

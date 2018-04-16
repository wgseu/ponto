<?php

class InstantiationTest extends \PHPUnit_Framework_TestCase
{
    public function testOldClasses()
    {
        $categoria = new \ZCategoria();
        $cliente = new \ZCliente();
        $composicao = new \ZComposicao();
        $conta = new \ZConta();
        $estoque = new \ZEstoque();
        $funcionario = new \ZFuncionario();
        $grupo = new \ZGrupo();
        $pagamento = new \ZPagamento();
        $pedido = new \ZPedido();
        $produto = new \ZProduto();
        $produto_pedido = new \ZProdutoPedido();
    }
}

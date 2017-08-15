<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::CADASTROPRODUTOS);
$produto = ZProduto::getPeloID($_GET['id']);
if (is_null($produto->getID())) {
    Thunder::warning('O produto de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/produto/');
}
if ($produto->getTipo() != ProdutoTipo::COMPOSICAO) {
    Thunder::warning('O produto "'.$produto->getDescricao().'" não é uma composição!');
    redirect('/gerenciar/produto/');
}
$nos = array();
$stack = new SplStack();
$composicao = new ZComposicao();
$composicao->setID(0); // código do pai
$composicao->setComposicaoID(null); // não possui pai
$composicao->setProdutoID($produto->getID());
$composicao->setQuantidade($produto->getConteudo());
$stack->push($composicao);
while (!$stack->isEmpty()) {
    $composicao = $stack->pop();
    $_produto = ZProduto::getPeloID($composicao->getProdutoID());
    if ($_produto->getTipo() == ProdutoTipo::PACOTE) {
        continue;
    }
    if ($_produto->getTipo() == ProdutoTipo::COMPOSICAO) {
        $valor = 0.0;
        $composicoes = ZComposicao::getTodasDaComposicaoID($composicao->getProdutoID());
        foreach ($composicoes as $_composicao) {
            if ($_composicao->getTipo() == ComposicaoTipo::ADICIONAL) {
                continue;
            }
            $_composicao->setQuantidade($_composicao->getQuantidade() * $composicao->getQuantidade());
            $_composicao->setComposicaoID($composicao->getID()); // salva o código do pai
            $stack->push($_composicao);
        }
    } else { // o composto é um produto
        $valor = ZEstoque::getUltimoPrecoCompra($_produto->getID());
    }
    $composicao->setValor($valor);
    $no = array();
    $no['produto'] = $_produto;
    $no['composicao'] = $composicao;
    $nos[$composicao->getID()] = $no;
}
foreach (array_reverse($nos) as $no) {
    $_composicao = $no['composicao'];
    if (is_null($_composicao->getComposicaoID())) {
        continue;
    }
    $composicao = $nos[$_composicao->getComposicaoID()]['composicao'];
    $total = $composicao->getValor() * $composicao->getQuantidade() +
        $_composicao->getValor() * $_composicao->getQuantidade();
    $composicao->setValor($total / $composicao->getQuantidade());
}
include template('gerenciar_produto_diagrama');

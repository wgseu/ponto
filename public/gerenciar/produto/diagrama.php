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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Product\Produto;
use MZ\Product\Composicao;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$produto = Produto::findByID($id);
if (!$produto->exists()) {
    $msg = 'O produto não foi informado ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/produto/');
}
if ($produto->getTipo() != Produto::TIPO_COMPOSICAO) {
    $msg = sprintf('O produto "%s" não é uma composição', $produto->getDescricao());
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/produto/');
}
$nos = [];
$stack = new \SplStack();
$composicao = new Composicao();
$composicao->setID(0); // código do pai
$composicao->setComposicaoID(null); // não possui pai
$composicao->setProdutoID($produto->getID());
$composicao->setQuantidade($produto->getConteudo());
$stack->push($composicao);
while (!$stack->isEmpty()) {
    $composicao = $stack->pop();
    $_produto = $composicao->findProdutoID();
    if ($_produto->getTipo() == Produto::TIPO_PACOTE) {
        continue;
    }
    if ($_produto->getTipo() == Produto::TIPO_COMPOSICAO) {
        $valor = 0.0;
        $composicoes = Composicao::findAll([
            'composicaoid' => $composicao->getProdutoID(),
            'tipo' => [Composicao::TIPO_COMPOSICAO, Composicao::TIPO_OPCIONAL],
            'ativa' => 'Y'
        ]);
        foreach ($composicoes as $_composicao) {
            if ($_composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                continue;
            }
            $_composicao->setQuantidade($_composicao->getQuantidade() * $composicao->getQuantidade());
            $_composicao->setComposicaoID($composicao->getID()); // salva o código do pai
            $stack->push($_composicao);
        }
    } else { // o composto é um produto
        $valor = Estoque::getUltimoPrecoCompra($_produto->getID());
    }
    $composicao->setValor($valor);
    $no = [];
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
$app->getResponse('html')->output('gerenciar_produto_diagrama');

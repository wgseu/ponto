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

need_permission(Permissao::NOME_CADASTROPRODUTOS);

$count = Produto::getCount(
    $_GET['query'],
    $_GET['categoria_id'],
    $_GET['unidade_id'],
    $_GET['tipo'],
    null, // estoque
    null, // setor de estoque
    null, // incluir promoção
    null, // visibilidade
    null, // mostrar com estoque limitado
    null  // pesável
);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$produtos = Produto::getTodos(
    $_GET['query'],
    $_GET['categoria_id'],
    $_GET['unidade_id'],
    $_GET['tipo'],
    null, // estoque
    null, // setor de estoque
    null, // incluir promoção
    null, // visibilidade
    null, // mostrar com estoque limitado
    null, // pesável
    false, // raw mode
    $offset,
    $pagesize
);

$tipos = Produto::getTipoOptions();
$categorias = [];
$_categorias = Categoria::getTodas(true);
foreach ($_categorias as $categoria) {
    $categorias[$categoria->getID()] = $categoria->getDescricao();
}
$unidades = [];
$_unidades = Unidade::findAll();
foreach ($_unidades as $unidade) {
    $unidades[$unidade->getID()] = $unidade->getNome();
}
include template('gerenciar_produto_index');

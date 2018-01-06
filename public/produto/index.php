<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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

$pagetitle = 'Produtos';
$categorias = ZCategoria::getTodas();
if (count($categorias) > 0) {
    $categoria_atual = current($categorias);
    $produtos = ZProduto::getTodos(
        null, // busca
        $categoria_atual->getID(),
        null, // unidade
        null, // tipo
        0, // estoque
        null, // setor de estoque
        null, // incluir promoção
        null, // visibilidade
        null, // mostrar com estoque limitado
        null, // pesável
        false // raw mode
    );
} else {
    $produtos = array();
    $categoria_atual = new ZCategoria();
}
include template('produto_index');
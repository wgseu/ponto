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

need_permission(array(PermissaoNome::ESTOQUE, PermissaoNome::RETIRARDOESTOQUE), $_GET['saida'] == 'json');
$id = $_GET['id'];
$estoque = ZEstoque::getPeloID($id);
if (is_null($estoque->getID())) {
    $msg = 'O estoque de id "'.$id.'" não existe!';
    if ($_GET['saida'] == 'json') {
        json($msg);
    }
    Thunder::warning($msg);
    redirect('/gerenciar/estoque/');
}
try {
    $produto = ZProduto::getPeloID($estoque->getProdutoID());
    $estoque->cancelar();
    $msg = 'Entrada do produto "' . $produto->getDescricao() . '" e quantidade ' . $estoque->getQuantidade() . ' cancelada com sucesso!';
    if ($_GET['saida'] == 'json') {
        json('msg', $msg);
    }
    Thunder::success($msg, true);
} catch (Exception $e) {
    $msg = $e->getMessage();
    if ($_GET['saida'] == 'json') {
        json($msg);
    }
    Thunder::error($msg);
}
redirect('/gerenciar/estoque/');

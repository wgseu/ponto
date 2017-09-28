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

need_permission(PermissaoNome::CADASTROMESAS, is_output('json'));
$id = $_GET['id'];
$mesa = ZMesa::getPeloID($id);
if (is_null($mesa->getID())) {
    $msg = 'A mesa de id "'.$id.'" não existe!';
    if (is_output('json')) {
        json($msg);
    }
    Thunder::warning($msg);
    redirect('/gerenciar/mesa/');
}
try {
    ZMesa::excluir($id);
    $msg = 'Mesa "' . $mesa->getNome() . '" excluída com sucesso!';
    if (is_output('json')) {
        json('msg', $msg);
    }
    Thunder::success($msg, true);
} catch (Exception $e) {
    $msg = 'Não foi possível excluir a mesa "' . $mesa->getNome() . '"!';
    if (is_output('json')) {
        json($msg);
    }
    Thunder::error($msg);
}
redirect('/gerenciar/mesa/');

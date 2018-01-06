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
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

$status = array();
$status['status'] = 'ok';
$status['versao'] = ZSistema::VERSAO;
$status['validacao'] = '';
$status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
if (is_manager()) {
    $status['acesso'] = 'funcionario';
} elseif (is_login()) {
    $status['acesso'] = 'cliente';
} else {
    $status['acesso'] = 'visitante';
}
if (is_login()) {
    try {
        if (is_manager()) {
            $dispositivo = register_device($_GET['device'], $_GET['serial']);
        } else {
            $dispositivo = new ZDispositivo();
        }
        $status['cliente'] = $login_cliente->getID();
        $status['funcionario'] = intval($login_funcionario->getID());
        $status['validacao'] = $dispositivo->getValidacao();
        $status['permissoes'] = ZAcesso::getPermissoes($login_funcionario->getID());
    } catch (Exception $e) {
        $status['status'] = 'error';
        $status['acesso'] = 'cliente';
        $status['msg'] = $e->getMessage();
    }
} else {
    $status['permissoes'] = array();
}
json($status);

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

if(!is_login())
	json('Usuário não autenticado!');
if(!have_permission(PermissaoNome::PEDIDOMESA))
	json('Você não tem permissão para acessar mesas');
/* verifica se deve ordenar pelo número da mesa ou pelo funcionário */
$funcionario_id = null;
if(!isset($_GET['ordenar']) && $_GET['ordenar'] != 'mesa')
	$funcionario_id = $login_funcionario_id;
$mesas = ZMesa::getTodas($funcionario_id);
$response = array('status' => 'ok');
$mesas_array = array();
foreach ($mesas as $mesa) {
	$mesas_array[] = $mesa->toArray();
}
$response['mesas'] = $mesas_array;
json($response);
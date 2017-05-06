<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do grupo, avisos de direitos autorais,
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

if(!isset($_GET['grupo']) || !is_numeric($_GET['grupo']))
	json('Grupo não informado!');
$pacotes = ZPacote::getTodosDoGrupoIDEx(intval($_GET['grupo']), $_POST['pacote'], strval($_GET['busca']));
$response = array('status' => 'ok');
$_pacotes = array();
foreach ($pacotes as $pacote) {
	$pacote['imagemurl'] = get_image_url($pacote['imagemurl'], (is_null($pacote['produtoid'])?'propriedade':'produto'), null);
	$_pacotes[] = $pacote;
}
$response['pacotes'] = $_pacotes;
json($response);
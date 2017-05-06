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

$estado_id = $_GET['estadoid'];
$estado = ZEstado::getPeloID($estado_id);
if(is_null($estado->getID()))
	json('O estado não foi informado ou não existe!');
$cidade = ZCidade::getPeloEstadoIDNome($estado_id, trim($_GET['cidade']));
if(is_null($cidade->getID()))
	json('A cidade "' . $_GET['cidade'] . '" não existe!');
if($_GET['tipo'] == 'logradouro')
	$localizacoes = ZLocalizacao::getTodasDaCidadeID($cidade->getID(), 'logradouro', $_GET['logradouro'], 0, 10);
else if($_GET['tipo'] == 'condominio')
	$localizacoes = ZLocalizacao::getTodasDaCidadeID($cidade->getID(), 'condominio', $_GET['condominio'], 0, 10);
else
	$localizacoes = array();
$_localizacoes = array();
$campos = array(
	'cep',
	'logradouro'
);
if($_GET['tipo'] == 'condominio') {
	$campos[] = 'numero';
	$campos[] = 'condominio';
}
foreach ($localizacoes as $localizacao) {
	$bairro = ZBairro::getPeloID($localizacao->getBairroID());
	$_localizacao = $localizacao->toArray();
	$_localizacao = array_intersect_key($_localizacao, array_flip($campos)); 
	$_localizacao['bairro'] = $bairro->getNome();
	$_localizacoes[] = $_localizacao;
}
json(array('status' => 'ok', 'items' => $_localizacoes));
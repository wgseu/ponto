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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!isset($_GET['produto']) || !is_numeric($_GET['produto'])) {
    json('Produto não informado!');
}
$limite = isset($_GET['limite'])?intval($_GET['limite']):null;
if (!is_null($limite) && $limite < 1) {
    $limite = null;
}
$composicoes = ZComposicao::getTodasDaComposicaoIDEx(
    strval($_GET['busca']),
    $_GET['produto'],
    intval($_GET['selecionaveis']) != 0,
    intval($_GET['adicionais']) != 0,
    0,
    $limite
);
$response = array('status' => 'ok');
$_composicoes = array();
foreach ($composicoes as $composicao) {
    $composicao['imagemurl'] = get_image_url($composicao['imagemurl'], 'produto', null);
    $_composicoes[] = $composicao;
}
$response['composicoes'] = $_composicoes;
json($response);

<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do cliente, avisos de direitos autorais,
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

need_manager(true);

$limit = intval($_GET['limite']);
if ($_GET['primeiro'] || check_fone($_GET['busca'], true)) {
    $limit = 1;
} elseif ($limit < 1) {
    $limit = 5;
} elseif ($limit > 20) {
    $limit = 20;
}
$clientes = ZCliente::getTodos(
    $_GET['busca'],
    $_GET['tipo'],
    null, // genero
    null, // mes_inicio
    null, // mes_fim
    null, // cpf
    null, // fone
    null, // email
    null, // birthday
    0,    // offset
    $limit // pagesize
);
$response = array('status' => 'ok');
$campos = array(
            'id',
            'tipo',
            'genero',
            'nome',
            'sobrenome',
            'fone1',
            'email',
            'cpf',
            'imagemurl',
        );
$_clientes = array();
$domask = intval($_GET['formatar']) != 0;
foreach ($clientes as $cliente) {
    $_cliente = $cliente->toArray();
    if ($domask) {
        $_cliente['fone1'] = \MZ\Util\Mask::phone($_cliente['fone1']);
    }
    $_cliente['imagemurl'] = get_image_url($_cliente['imagem'], 'cliente', null);
    $_clientes[] = array_intersect_key($_cliente, array_flip($campos));
}
$response['clientes'] = $_clientes;
json($response);
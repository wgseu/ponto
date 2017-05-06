<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O fornecedor não deverá remover qualquer identificação do fornecedor, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O fornecedor não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O fornecedor adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager(true);

$limit = intval($_GET['limite']);
if($_GET['primeiro'] || check_fone($_GET['busca'], true))
	$limit = 1;
else if($limit < 1)
	$limit = 5;
else if($limit > 20)
	$limit = 20;
$fornecedores = ZFornecedor::getTodos($_GET['busca'], 0, $limit);
$response = array('status' => 'ok');
$campos = array(
			'id',
			'nome',
			'fone1',
			'cnpj',
			'email',
			'prazopagamento',
			'imagemurl',
		);
$_fornecedores = array();
$domask = intval($_GET['format']) != 0;
foreach ($fornecedores as $fornecedor) {
	$_fornecedor = $fornecedor->toArray();
	$cliente = ZCliente::getPeloID($fornecedor->getEmpresaID());
	$_fornecedor['nome'] = $cliente->getNome();
	$_fornecedor['fone1'] = $cliente->getFone(1);
	$_fornecedor['cnpj'] = $cliente->getCPF();
	$_fornecedor['email'] = $cliente->getEmail();
	if($domask)
		$_fornecedor['fone1'] = mask($_fornecedor['fone1'], '(99) 9999-9999?9'); 
	$_fornecedor['imagemurl'] = get_image_url($cliente->getImagem(), 'cliente', null);
	$_fornecedores[] = array_intersect_key($_fornecedor, array_flip($campos));
}
$response['items'] = $_fornecedores;
json($response);
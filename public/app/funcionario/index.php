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
if ($_GET['primeiro'] || check_fone($_GET['busca'], true)) {
    $limit = 1;
} elseif ($limit < 1) {
    $limit = 5;
} elseif ($limit > 20) {
    $limit = 20;
}
$funcionarios = ZFuncionario::getTodos($_GET['busca'], null, null, $_GET['ativo'], 0, $limit);
$response = array('status' => 'ok');
$campos = array(
            'id',
            'nome',
            'fone1',
            'cpf',
            'email',
            'funcao',
            'imagemurl',
        );
$_funcionarios = array();
$domask = intval($_GET['format']) != 0;
foreach ($funcionarios as $funcionario) {
    $_funcionario = $funcionario->toArray();
    $funcao = ZFuncao::getPeloID($funcionario->getFuncaoID());
    $cliente = ZCliente::getPeloID($funcionario->getClienteID());
    $_funcionario['nome'] = $cliente->getNomeCompleto();
    $_funcionario['fone1'] = $cliente->getFone(1);
    $_funcionario['cpf'] = $cliente->getCPF();
    $_funcionario['email'] = $cliente->getEmail();
    $_funcionario['funcao'] = $funcao->getDescricao();
    if ($domask) {
        $_funcionario['fone1'] = \MZ\Util\Mask::phone($_funcionario['fone1']);
    }
    $_funcionario['imagemurl'] = get_image_url($cliente->getImagem(), 'cliente', null);
    $_funcionarios[] = array_intersect_key($_funcionario, array_flip($campos));
}
$response['items'] = $_funcionarios;
json($response);

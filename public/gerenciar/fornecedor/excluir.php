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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Stock\Fornecedor;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROFORNECEDORES, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$fornecedor = Fornecedor::findByID($id);
if (!$fornecedor->exists()) {
    $msg = 'O fornecedor não foi informado ou não existe';
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::warning($msg);
	redirect('/gerenciar/fornecedor/');
}
$empresa_id_obj = $fornecedor->findEmpresaID();
try {
	$fornecedor->delete();
	$fornecedor->clean(new Fornecedor());
	$msg = sprintf('Fornecedor "%s" excluído com sucesso!', $empresa_id_obj->getNomeCompleto());
	if (is_output('json')) {
		json('msg', $msg);
	}
	\Thunder::success($msg, true);
} catch (\Exception $e) {
	$msg = sprintf(
		'Não foi possível excluir o fornecedor "%s"',
		$empresa_id_obj->getNomeCompleto()
	);
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::error($msg);
}
redirect('/gerenciar/fornecedor/');

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

use MZ\__TODO_NAMESPACE__\Fornecedor;

need_permission(\Permissao::NOME_CADASTROFORNECEDORES, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$fornecedor = Fornecedor::findByID($id);
if (!$fornecedor->exists()) {
    $msg = 'O fornecedor informado não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/fornecedor/');
}
$focusctrl = 'empresa';
$errors = [];
$old_fornecedor = $fornecedor;
if (is_post()) {
    $fornecedor = new Fornecedor($_POST);
    try {
        $fornecedor->setID($old_fornecedor->getID());
        $fornecedor->setPrazoPagamento(numberval($fornecedor->getPrazoPagamento()));
        $fornecedor->filter($old_fornecedor);
        $fornecedor->update();
        $old_fornecedor->clean($fornecedor);
        $msg = sprintf(
            'Fornecedor "%s" atualizado com sucesso!',
            $fornecedor->getEmpresaID()
        );
        if (is_output('json')) {
            json(null, ['item' => $fornecedor->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/fornecedor/');
    } catch (\Exception $e) {
        $fornecedor->clean($old_fornecedor);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        if (is_output('json')) {
            json($e->getMessage(), null, ['errors' => $errors]);
        }
        \Thunder::error($e->getMessage());
        foreach ($errors as $key => $value) {
            $focusctrl = $key;
            break;
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}
if ($focusctrl == 'empresaid') {
    $focusctrl == 'empresa';
}
include template('gerenciar_fornecedor_editar');

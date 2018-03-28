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

use MZ\__TODO_NAMESPACE__\Mesa;

need_permission(\Permissao::NOME_CADASTROMESAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$mesa = Mesa::findByID($id);
$mesa->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_mesa = $mesa;
if (is_post()) {
    $mesa = new Mesa($_POST);
    try {
        $mesa->filter($old_mesa);
        $mesa->insert();
        $old_mesa->clean($mesa);
        $msg = sprintf(
            'Mesa "%s" cadastrada com sucesso!',
            $mesa->getNome()
        );
        if (is_output('json')) {
            json(null, ['item' => $mesa->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/mesa/');
    } catch (\Exception $e) {
        $mesa->clean($old_mesa);
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
} else {
    $mesa = new Mesa();
    $mesa->setID(Mesa::getProximoID());
    $mesa->setNome('Mesa ' . $mesa->getID());
    $mesa->setAtiva('Y');
}
include template('gerenciar_mesa_cadastrar');

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

need_permission(Permissao::NOME_CADASTROCLIENTES);
$id = $_GET['id'];
$cliente = Cliente::findByID($id);
if (is_null($cliente->getID())) {
    $msg = 'O cliente de id "'.$id.'" não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
if ($cliente->getID() == $__empresa__->getID() &&
    !$login_funcionario->has(Permissao::NOME_ALTERARCONFIGURACOES)) {
    $msg = 'Você não tem permissão para excluir essa empresa!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
$funcionario = Funcionario::findByClienteID($cliente->getID());
if (!is_null($funcionario->getID()) && (
    (!$login_funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
     $login_funcionario->getID() != $funcionario->getID()) ||
    ( have_permission(Permissao::NOME_CADASTROFUNCIONARIOS, $funcionario) &&
     $login_funcionario->getID() != $funcionario->getID() && !is_owner()) ) ) {
    $msg = 'Você não tem permissão para excluir esse cliente!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
try {
    Cliente::excluir($id);
    $msg = 'Cliente "' . $cliente->getNomeCompleto() . '" excluído com sucesso!';
    if (is_output('json')) {
        json('msg', $msg);
    }
    \Thunder::success($msg, true);
} catch (Exception $e) {
    $msg = 'Não foi possível excluir o cliente "' . $cliente->getNomeCompleto() . '"!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::error($msg);
}
redirect('/gerenciar/cliente/');

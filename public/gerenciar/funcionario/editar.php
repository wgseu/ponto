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

need_manager();
$funcionario = Funcionario::findByID($_GET['id']);
if (is_null($funcionario->getID())) {
    \Thunder::warning('O(a) funcionário(a) de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/funcionario/');
}
if ((!$login_funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
    !is_self($funcionario)) ||
   ( have_permission(Permissao::NOME_CADASTROFUNCIONARIOS, $funcionario) &&
    !is_self($funcionario) && !is_owner()) ) {
    \Thunder::warning('Você não tem permissão para alterar as informações desse(a) funcionário(a)!');
    redirect('/gerenciar/funcionario/');
}
$cliente_func = $funcionario->findClienteID();
$focusctrl = 'clienteid';
$errors = [];
$old_funcionario = $funcionario;
if (is_post()) {
    $funcionario = new Funcionario($_POST);
    try {
        $funcionario->setPorcentagem(moneyval($funcionario->getPorcentagem()));
        $funcionario->setLinguagemID(numberval($funcionario->getLinguagemID()));
        $funcionario->setPontuacao(numberval($funcionario->getPontuacao()));
        $funcionario->setID($old_funcionario->getID()); // não permite alterar o código
        $funcionario->setDataSaida($old_funcionario->getDataSaida()); // não altera a data de saida
        if (is_owner($old_funcionario) || is_self($old_funcionario)) {
            $funcionario->setClienteID($old_funcionario->getClienteID());
            $funcionario->setFuncaoID($old_funcionario->getFuncaoID());
            $funcionario->setAtivo($old_funcionario->getAtivo());
            if (!is_owner($old_funcionario)) {
                $funcionario->setPorcentagem($old_funcionario->getPorcentagem());
                $funcionario->setCodigoBarras($old_funcionario->getCodigoBarras());
                $funcionario->setPontuacao($old_funcionario->getPontuacao());
            }
        }
        $funcionario->filter($old_funcionario);
        $funcionario->update();
        $old_funcionario->clean($funcionario);
        $msg = sprintf(
            'Funcionário(a) "%s" atualizado(a) com sucesso!',
            $cliente_func->getLogin()
        );
        if (is_output('json')) {
            json(null, ['item' => $cliente_func->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/funcionario/');
    } catch (\Exception $e) {
        $funcionario->clean($old_funcionario);
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
if ($focusctrl == 'clienteid') {
    $focusctrl = 'cliente';
}
$_funcoes = Funcao::findAll();
$linguagens = get_languages_info();
include template('gerenciar_funcionario_editar');

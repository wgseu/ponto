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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager();
$funcionario = ZFuncionario::getPeloID($_GET['id']);
if (is_null($funcionario->getID())) {
    Thunder::warning('O(a) funcionário(a) de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/funcionario/');
}
if ((!have_permission(PermissaoNome::CADASTROFUNCIONARIOS) &&
    !is_self($funcionario)) ||
   ( have_permission(PermissaoNome::CADASTROFUNCIONARIOS, $funcionario) &&
    !is_self($funcionario) && !is_owner()) ) {
    Thunder::warning('Você não tem permissão para alterar as informações desse(a) funcionário(a)!');
    redirect('/gerenciar/funcionario/');
}
$cliente_func = ZCliente::getPeloID($funcionario->getClienteID());
$focusctrl = 'clienteid';
$errors = array();
$old_funcionario = $funcionario;
if ($_POST) {
    $funcionario = new ZFuncionario($_POST);
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
        $funcionario = ZFuncionario::atualizar($funcionario);
        Thunder::success('Funcionário(a) "'.$cliente_func->getLogin().'" atualizado(a) com sucesso!', true);
        redirect('/gerenciar/funcionario/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
}
if ($focusctrl == 'clienteid') {
    $focusctrl = 'cliente';
}
$_funcoes = ZFuncao::getTodas();
$linguagens = get_languages_info();
include template('gerenciar_funcionario_editar');

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

need_owner(is_output('json'));
$funcao = ZFuncao::getPeloID($_GET['funcao']?:$_POST['funcao']);
if (is_null($funcao->getID())) {
    if (is_output('json')) {
        json('A função não foi informada ou não existe');
    }
    redirect('/gerenciar/funcao/');
}
$errors = [];
if (is_post()) {
    try {
        $permissao = ZPermissao::getPeloID($_POST['permissao']);
        if (is_null($permissao->getID())) {
            throw new Exception('A permissão informada não existe', 1);
        }
        if ($_POST['marcado'] == 'Y') {
            $acesso = new ZAcesso();
            $acesso->setFuncaoID($funcao->getID());
            $acesso->setPermissaoID($permissao->getID());
            $acesso = ZAcesso::cadastrar($acesso);
        } else {
            $acesso = ZAcesso::getPelaFuncaoIDPermissaoID($funcao->getID(), $permissao->getID());
            ZAcesso::excluir($acesso->getID());
        }
        if (is_output('json')) {
            json(['status' => 'ok']);
        }
        redirect('/gerenciar/acesso/?funcao='.$funcao->getID());
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        if (is_output('json')) {
            json($value);
        }
        Thunder::error($value);
        break;
    }
}
$permissoes = ZPermissao::getTodas($_GET['query']);
if (is_output('json')) {
    $_permissoes = [];
    foreach ($permissoes as $permissao) {
        $_permissao = $permissao->toArray();
        $_acesso = ZAcesso::getPelaFuncaoIDPermissaoID($funcao->getID(), $permissao->getID());
        $_permissao['marcado'] = is_null($_acesso->getID())?'N':'Y';
        $_permissoes[] = $_permissao;
    }
    json(['status' => 'ok', 'items' => $_permissoes]);
}
include template('gerenciar_acesso_index');

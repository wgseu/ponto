<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (is_login()) {
    if ($weblogin) {
        redirect('/');
    }
    if (is_manager()) {
        try {
            $dispositivo = register_device($_POST['device'], $_POST['serial']);
        } catch (Exception $e) {
            json($e->getMessage());
        }
    } else {
        $dispositivo = new ZDispositivo();
    }
    $status = array('status' => 'ok', 'msg' => 'Já está autenticado');
    $status['versao'] = ZSistema::VERSAO;
    $status['cliente'] = $login_cliente->getID();
    $status['funcionario'] = intval($login_funcionario->getID());
    $status['validacao'] = $dispositivo->getValidacao();
    $status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
    $status['permissoes'] = ZAcesso::getPermissoes($login_funcionario->getID());
    json($status);
}
if (!is_post()) {
    json('Método incorreto');
}
$usuario = strval($_POST['usuario']);
$senha = strval($_POST['senha']);
$lembrar = strval($_POST['lembrar']);
$metodo = strval($_POST['metodo']);
$token = strval($_POST['token']);
if ($metodo == 'desktop') {
    $cliente = ZCliente::getPeloToken($token);
} else {
    $cliente = ZCliente::getPeloLoginSenha($usuario, $senha);
}
if (is_null($cliente->getID())) {
    if ($metodo == 'desktop') {
        $msg = 'Token inválido!';
    } else {
        $msg = 'Usuário ou senha incorretos!';
    }
    if ($weblogin) {
        Thunder::error($msg);
        exit(include template('conta_entrar'));
    } else {
        json($msg);
    }
}
$funcionario = ZFuncionario::getPeloClienteID($cliente->getID());
if ((is_null($weblogin) || !$weblogin) && !is_null($funcionario->getID())) {
    if (!ZAcesso::temPermissao($funcionario->getFuncaoID(), PermissaoNome::SISTEMA)) {
        json('Você não tem permissão para acessar o sistema!');
    }
    try {
        $dispositivo = register_device($_POST['device'], $_POST['serial']);
    } catch (Exception $e) {
        json($e->getMessage());
    }
} else {
    $dispositivo = new ZDispositivo();
}
$login_cliente = $cliente;
$login_cliente_id = $cliente->getID();
$login_funcionario = $funcionario;
$login_funcionario_id = $funcionario->getID();
ZAutenticacao::login($cliente->getID());
if ($lembrar == 'true') {
    ZAutenticacao::lembrar($login_cliente);
}
if ($weblogin) {
    $url = is_null($_POST['redirect'])?'/':strval($_POST['redirect']);
    redirect($url);
}
$status = array('status' => 'ok', 'msg' => 'Login efetuado com sucesso!');
$status['versao'] = ZSistema::VERSAO;
$status['cliente'] = $login_cliente->getID();
$status['funcionario'] = intval($login_funcionario->getID());
$status['validacao'] = $dispositivo->getValidacao();
$status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
$status['permissoes'] = ZAcesso::getPermissoes($login_funcionario->getID());
json($status);

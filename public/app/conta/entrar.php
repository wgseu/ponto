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

if (!is_post()) {
    json('Método incorreto');
}
$usuario = strval($_POST['usuario']);
$senha = strval($_POST['senha']);
$lembrar = strval($_POST['lembrar']);
$metodo = strval($_POST['metodo']);
$token = strval($_POST['token']);
if ($metodo == 'desktop') {
    $cliente = Cliente::findByToken($token);
} else {
    $cliente = Cliente::findByLoginSenha($usuario, $senha);
}
if (is_null($cliente->getID())) {
    if ($metodo == 'desktop') {
        $msg = 'Token inválido!';
    } else {
        $msg = 'Usuário ou senha incorretos!';
    }
    if ($weblogin) {
        \Thunder::error($msg);
        exit(include template('conta_entrar'));
    } else {
        json($msg);
    }
}
$funcionario = Funcionario::findByClienteID($cliente->getID());
if ((is_null($weblogin) || !$weblogin) && !is_null($funcionario->getID())) {
    if (!Acesso::temPermissao($funcionario->getFuncaoID(), Permissao::NOME_SISTEMA)) {
        json('Você não tem permissão para acessar o sistema!');
    }
    try {
        $dispositivo = register_device($_POST['device'], $_POST['serial']);
    } catch (Exception $e) {
        json($e->getMessage());
    }
} else {
    $dispositivo = new Dispositivo();
}

if (is_login()) {
    Authentication::logout();
}
$login_cliente = $cliente;
$login_cliente_id = $cliente->getID();
$login_funcionario = $funcionario;
$login_funcionario_id = $funcionario->getID();
Authentication::login($cliente->getID());
if ($lembrar == 'true') {
    Authentication::lembrar($login_cliente);
}
if ($weblogin) {
    $url = is_null($_POST['redirect'])?'/':strval($_POST['redirect']);
    redirect($url);
}
$status = ['status' => 'ok', 'msg' => 'Login efetuado com sucesso!'];
$status['versao'] = Sistema::VERSAO;
$status['cliente'] = $login_cliente->getID();
$status['info'] = [
    'usuario' => [
        'nome' => $login_cliente->getNome(),
        'email' => $login_cliente->getEmail(),
        'login' => $login_cliente->getLogin(),
        'imagemurl' => get_image_url($login_cliente->getImagem(), 'cliente', null)
    ]
];
$status['funcionario'] = intval($login_funcionario->getID());
$status['validacao'] = $dispositivo->getValidacao();
$status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
if (is_manager()) {
    $status['acesso'] = 'funcionario';
} elseif (is_login()) {
    $status['acesso'] = 'cliente';
} else {
    $status['acesso'] = 'visitante';
}
$status['permissoes'] = Acesso::getPermissoes($login_funcionario->getID());
json($status);

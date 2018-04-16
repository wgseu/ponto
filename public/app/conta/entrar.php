<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\Account\Cliente;
use MZ\Employee\Funcionario;
use MZ\Device\Dispositivo;
use MZ\System\Permissao;
use MZ\Account\Authentication;
use MZ\System\Sistema;
use MZ\Employee\Acesso;

if (!is_post()) {
    json('Método incorreto');
}
$usuario = isset($_POST['usuario']) ? strval($_POST['usuario']) : null;
$senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
$lembrar = isset($_POST['lembrar']) ? strval($_POST['lembrar']) : null;
$metodo = isset($_POST['metodo']) ? strval($_POST['metodo']) : null;
$token = isset($_POST['token']) ? strval($_POST['token']) : null;
if ($metodo == 'desktop') {
    $cliente = Cliente::findByToken($token);
} else {
    $cliente = Cliente::findByLoginSenha($usuario, $senha);
}
if (!$cliente->exists()) {
    if ($metodo == 'desktop') {
        $msg = 'Token inválido!';
    } else {
        $msg = 'Usuário ou senha incorretos!';
    }
    if ($weblogin) {
        \Thunder::error($msg);
        exit($app->getResponse('html')->output('conta_entrar'));
    } else {
        json($msg);
    }
}
$funcionario = Funcionario::findByClienteID($cliente->getID());
if ((is_null($weblogin) || !$weblogin) && $funcionario->exists()) {
    if (!$funcionario->has(Permissao::NOME_SISTEMA)) {
        json('Você não tem permissão para acessar o sistema!');
    }
    try {
        $device = isset($_POST['device']) ? $_POST['device'] : null;
        $serial = isset($_POST['serial']) ? $_POST['serial'] : null;
        $dispositivo = register_device($device, $serial);
    } catch (\Exception $e) {
        json($e->getMessage());
    }
} else {
    $dispositivo = new Dispositivo();
}

if (is_login()) {
    $app->getAuthentication()->logout();
}
$app->getAuthentication()->login($cliente);
if ($lembrar == 'true') {
    $app->getAuthentication()->remember();
}
if ($weblogin) {
    $url = isset($_POST['redirect']) ? strval($_POST['redirect']) : '/';
    redirect($url);
}
$status = ['status' => 'ok', 'msg' => 'Login efetuado com sucesso!'];
$status['versao'] = Sistema::VERSAO;
$status['cliente'] = logged_user()->getID();
$status['info'] = [
    'usuario' => [
        'nome' => logged_user()->getNome(),
        'email' => logged_user()->getEmail(),
        'login' => logged_user()->getLogin(),
        'imagemurl' => get_image_url(logged_user()->getImagem(), 'cliente', null)
    ]
];
$status['funcionario'] = intval(logged_employee()->getID());
$status['validacao'] = $dispositivo->getValidacao();
$status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
if (is_manager()) {
    $status['acesso'] = 'funcionario';
} elseif (is_login()) {
    $status['acesso'] = 'cliente';
} else {
    $status['acesso'] = 'visitante';
}
$status['permissoes'] = $app->getAuthentication()->getPermissions();
json($status);

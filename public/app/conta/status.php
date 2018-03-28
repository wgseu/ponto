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

$status = [];
$status['status'] = 'ok';
$status['info'] = [
    'empresa' => [
        'nome' => $__empresa__->getNome(),
        'imagemurl' => get_image_url($__empresa__->getImagem(), 'cliente', null)
    ]
];
$status['versao'] = Sistema::VERSAO;
$status['validacao'] = '';
$status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
if (is_manager()) {
    $status['acesso'] = 'funcionario';
} elseif (is_login()) {
    $status['acesso'] = 'cliente';
} else {
    $status['acesso'] = 'visitante';
}
if (is_login()) {
    $status['cliente'] = $login_cliente->getID();
    $status['info']['usuario'] = [
        'nome' => $login_cliente->getNome(),
        'email' => $login_cliente->getEmail(),
        'login' => $login_cliente->getLogin(),
        'imagemurl' => get_image_url($login_cliente->getImagem(), 'cliente', null)
    ];
    $status['funcionario'] = intval($login_funcionario->getID());
    try {
        $status['permissoes'] = Acesso::getPermissoes($login_funcionario->getID());
        if (is_manager()) {
            $dispositivo = register_device(
                isset($_GET['device'])?$_GET['device']:null,
                isset($_GET['serial'])?$_GET['serial']:null
            );
        } else {
            $dispositivo = new Dispositivo();
        }
        $status['validacao'] = $dispositivo->getValidacao();
    } catch (Exception $e) {
        $status['status'] = 'error';
        $status['msg'] = $e->getMessage();
    }
} else {
    $status['permissoes'] = [];
}
json($status);

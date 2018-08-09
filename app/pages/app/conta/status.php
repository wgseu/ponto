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
use MZ\System\Sistema;
use MZ\Device\Dispositivo;

$company = $app->getSystem()->getCompany();
$status = [];
$status['status'] = 'ok';
$status['info'] = [
    'empresa' => [
        'nome' => $company->getNome(),
        'imagemurl' => $company->makeImagem(false, null)
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
    $status['cliente'] = logged_user()->getID();
    $status['info']['usuario'] = [
        'nome' => logged_user()->getNome(),
        'email' => logged_user()->getEmail(),
        'login' => logged_user()->getLogin(),
        'imagemurl' => get_image_url(logged_user()->getImagem(), 'cliente', null)
    ];
    $status['funcionario'] = intval(logged_employee()->getID());
    try {
        $status['permissoes'] = $app->getAuthentication()->getPermissions();
        $dispositivo = new Dispositivo();
        if (is_manager()) {
            $dispositivo->setNome(isset($_GET['device']) ? $_GET['device'] : null);
            $dispositivo->setSerial(isset($_GET['serial']) ? $_GET['serial'] : null);
            $dispositivo->register();
        }
        $status['validacao'] = $dispositivo->getValidacao();
    } catch (\Exception $e) {
        $status['status'] = 'error';
        $status['msg'] = $e->getMessage();
    }
    $status['token'] = $app->getAuthentication()->updateAuthorization();
} else {
    $status['permissoes'] = [];
}
json($status);

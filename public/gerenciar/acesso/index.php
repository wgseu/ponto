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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Employee\Acesso;
use MZ\Employee\Funcao;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_owner(is_output('json'));
$funcao_id = isset($_GET['funcao']) ? $_GET['funcao'] : null;
$funcao_id = isset($_POST['funcao']) ? $_POST['funcao'] : $funcao_id;
$funcao = Funcao::findByID($funcao_id);
if (!$funcao->exists()) {
    if (is_output('json')) {
        json('A função não foi informada ou não existe');
    }
    redirect('/gerenciar/funcao/');
}
$errors = [];
if (is_post()) {
    try {
        $permissao_id = isset($_POST['permissao']) ? $_POST['permissao'] : null;
        $permissao = Permissao::findByID($permissao_id);
        if (!$permissao->exists()) {
            throw new \Exception('A permissão não foi informada ou não existe', 404);
        }
        if (isset($_POST['marcado']) && $_POST['marcado'] == 'Y') {
            $acesso = new Acesso();
            $acesso->setFuncaoID($funcao->getID());
            $acesso->setPermissaoID($permissao->getID());
            $acesso->insert();
        } else {
            $acesso = Acesso::find([
                'funcaoid' => $funcao->getID(),
                'permissaoid' => $permissao->getID()
            ]);
            $acesso->delete();
        }
        if (is_output('json')) {
            json(['status' => 'ok']);
        }
        redirect('/gerenciar/acesso/?funcao=' . $funcao->getID());
    } catch (\Exception $e) {
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
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$order = Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
$permissoes = Permissao::findAll($condition, $order);

if (is_output('json')) {
    $items = [];
    foreach ($permissoes as $permissao) {
        $item = $permissao->publish();
        $acesso = Acesso::find([
            'funcaoid' => $funcao->getID(),
            'permissaoid' => $permissao->getID()
        ]);
        $item['marcado'] = $acesso->exists() ? 'Y' : 'N';
        $items[] = $item;
    }
    json(['status' => 'ok', 'items' => $items]);
}
$app->getResponse('html')->output('gerenciar_acesso_index');

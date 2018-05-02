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

use MZ\Location\Cidade;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROCIDADES, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$cidade = Cidade::findByID($id);
$cidade->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_cidade = $cidade;
if (is_post()) {
    $cidade = new Cidade($_POST);
    try {
        $cidade->filter($old_cidade);
        $cidade->save();
        $old_cidade->clean($cidade);
        $msg = sprintf(
            'Cidade "%s" atualizada com sucesso!',
            $cidade->getNome()
        );
        if (is_output('json')) {
            json(null, ['item' => $cidade->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/cidade/');
    } catch (\Exception $e) {
        $cidade->clean($old_cidade);
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
if (is_null($cidade->getEstadoID())) {
    $cidade->setEstadoID($app->getSystem()->getState()->getID());
}
$_estado = $cidade->findEstadoID();
$_paises = \MZ\Location\Pais::findAll();
if ($_estado->exists()) {
    $pais = $_estado->findPaisID();
} elseif (count($_paises) > 0) {
    $pais = current($_paises);
} else {
    $pais = new \MZ\Location\Pais();
}
$_estados = \MZ\Location\Estado::findAll();
$app->getResponse('html')->output('gerenciar_cidade_cadastrar');

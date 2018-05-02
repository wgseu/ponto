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

use MZ\Environment\Patrimonio;
use MZ\System\Permissao;
use MZ\Database\Helper;

need_permission(Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$patrimonio = Patrimonio::findByID($id);
$patrimonio->setID(null);
$patrimonio->setImagemAnexada(null);

$focusctrl = 'descricao';
$errors = [];
$old_patrimonio = $patrimonio;
if (is_post()) {
    $patrimonio = new Patrimonio($_POST);
    try {
        $patrimonio->filter($old_patrimonio);
        $patrimonio->insert();
        $old_patrimonio->clean($patrimonio);
        $msg = sprintf(
            'Patrimônio "%s" cadastrado com sucesso!',
            $patrimonio->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $patrimonio->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/patrimonio/');
    } catch (\Exception $e) {
        $patrimonio->clean($old_patrimonio);
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
$app->getResponse('html')->output('gerenciar_patrimonio_cadastrar');

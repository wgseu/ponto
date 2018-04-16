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

use MZ\Session\Caixa;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROCAIXAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$caixa = Caixa::findByID($id);
if (!$caixa->exists()) {
    $msg = 'O caixa informado não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/caixa/');
}
$focusctrl = 'descricao';
$errors = [];
$old_caixa = $caixa;
if (is_post()) {
    $caixa = new Caixa($_POST);
    try {
        $caixa->setID($old_caixa->getID());
        if (!$app->getSystem()->isFiscalVisible()) {
            $caixa->setNumeroInicial($old_caixa->getNumeroInicial());
            $caixa->setSerie($old_caixa->getSerie());
        }
        $caixa->filter($old_caixa);
        $caixa->update();
        $old_caixa->clean($caixa);
        $msg = sprintf(
            'Caixa "%s" atualizado com sucesso!',
            $caixa->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $caixa->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/caixa/');
    } catch (\Exception $e) {
        $caixa->clean($old_caixa);
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
$app->getResponse('html')->output('gerenciar_caixa_editar');

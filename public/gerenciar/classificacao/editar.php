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

use MZ\Account\Classificacao;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$classificacao = Classificacao::findByID($id);
if (!$classificacao->exists()) {
    $msg = 'A classificação não foi informada ou não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/classificacao/');
}
$focusctrl = 'descricao';
$errors = [];
$old_classificacao = $classificacao;
if (is_post()) {
    $classificacao = new Classificacao($_POST);
    try {
        $classificacao->setID($old_classificacao->getID());
        $classificacao->filter($old_classificacao);
        $classificacao->update();
        $old_classificacao->clean($classificacao);
        $msg = sprintf(
            'Classificação "%s" atualizada com sucesso!',
            $classificacao->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $classificacao->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/classificacao/');
    } catch (\Exception $e) {
        $classificacao->clean($old_classificacao);
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
$_classificacoes = Classificacao::findAll(['classificacaoid' => null]);
$app->getResponse('html')->output('gerenciar_classificacao_editar');

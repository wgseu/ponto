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

use MZ\Account\Credito;
use MZ\System\Permissao;
use MZ\Database\Helper;

need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$credito = Credito::findByID($id);
$credito->setID(null);
$credito->setDataCadastro(Helper::now());

$focusctrl = 'detalhes';
$errors = [];
$old_credito = $credito;
if (is_post()) {
    $credito = new Credito($_POST);
    try {
        $old_credito->setFuncionarioID(logged_employee()->getID());
        $old_credito->setClienteID($credito->getClienteID());
        $credito->filter($old_credito);
        $credito->insert();
        $old_credito->clean($credito);
        $msg = sprintf(
            'Crédito "%s" cadastrado com sucesso!',
            $credito->getDetalhes()
        );
        if (is_output('json')) {
            json(null, ['item' => $credito->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/credito/');
    } catch (\Exception $e) {
        $credito->clean($old_credito);
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
$app->getResponse('html')->output('gerenciar_credito_cadastrar');

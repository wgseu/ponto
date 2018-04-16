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

use MZ\Location\Estado;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROESTADOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$estado = Estado::findByID($id);
$estado->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_estado = $estado;
if (is_post()) {
    $estado = new Estado($_POST);
    try {
        $estado->filter($old_estado);
        $estado->save();
        $old_estado->clean($estado);
        $msg = sprintf(
            'Estado "%s" atualizado com sucesso!',
            $estado->getNome()
        );
        if (is_output('json')) {
            json(null, ['item' => $estado->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/estado/');
    } catch (\Exception $e) {
        $estado->clean($old_estado);
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
$_paises = \MZ\Location\Pais::findAll();
$app->getResponse('html')->output('gerenciar_estado_cadastrar');

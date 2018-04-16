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

use MZ\System\Modulo;
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);
$id = isset($_GET['id']) ? $_GET['id'] : null;
$modulo = Modulo::findByID($id);
if (!$modulo->exists()) {
    $msg = 'O módulo não foi informado ou não existe';
    json($msg);
}
$focusctrl = 'nome';
$errors = [];
$old_modulo = $modulo;
if (is_post()) {
    $modulo = new Modulo($_POST);
    try {
        \DB::BeginTransaction();
        $modulo->filter($old_modulo);
        $modulo->update();
        $old_modulo->clean($modulo);
        try {
            $appsync = new \MZ\System\Synchronizer();
            $appsync->systemOptionsChanged();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        \DB::Commit();
        $msg = sprintf(
            'Módulo "%s" atualizado com sucesso!',
            $modulo->getNome()
        );
        json(null, ['item' => $modulo->publish(), 'msg' => $msg]);
    } catch (\Exception $e) {
        \DB::Rollback();
        $modulo->clean($old_modulo);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        json($e->getMessage(), null, ['errors' => $errors]);
    }
} else {
    json('Nenhum dado foi enviado');
}

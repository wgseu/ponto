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

use MZ\System\Integracao;

need_permission(\Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));
$id = isset($_GET['id'])?$_GET['id']:null;
$integracao = Integracao::findByID($id);
if (!$integracao->exists()) {
    $msg = 'Não existe Integração com o ID informado!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/integracao/');
}
$focusctrl = 'nome';
$errors = [];
$old_integracao = $integracao;
if (is_post()) {
    $integracao = new Integracao($_POST);
    try {
        $integracao->setAtivo($old_integracao->getAtivo());
        $integracao->filter($old_integracao);
        $integracao->save();
        $old_integracao->clean($integracao);
        $msg = sprintf(
            'Integração "%s" atualizada com sucesso!',
            $integracao->getNome()
        );
        if (is_output('json')) {
            json(null, ['item' => $integracao->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/integracao/');
    } catch (\Exception $e) {
        $integracao->clean($old_integracao);
        if ($e instanceof \ValidationException) {
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
include template('gerenciar_integracao_editar');

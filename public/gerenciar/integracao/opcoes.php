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

need_permission(\PermissaoNome::ALTERARCONFIGURACOES, true);
if (!$_POST) {
    json('Nenhum dado foi enviado');
}
$id = isset($_POST['id'])?$_POST['id']:null;
$integracao = Integracao::findByID($id);
if (!$integracao->exists()) {
    $msg = 'Não existe Integração com o ID informado!';
    json($msg);
}
$old_integracao = $integracao;
    $integracao = new Integracao($_POST);
try {
    $integracao->filter($old_integracao);
    $integracao->save();
    $old_integracao->clean($integracao);
    $msg = sprintf(
        'Integração "%s" atualizada com sucesso!',
        $integracao->getNome()
    );
    json(null, array('item' => $integracao->publish(), 'msg' => $msg));
} catch (\Exception $e) {
    $integracao->clean($old_integracao);
    $errors = array();
    if ($e instanceof \ValidationException) {
        $errors = $e->getErrors();
    }
    json($e->getMessage(), null, array('errors' => $errors));
}

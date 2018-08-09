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
use MZ\System\Integracao;
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);
if (!is_post()) {
    json('Nenhum dado foi enviado');
}
$id = isset($_POST['id'])?$_POST['id']:null;
$integracao = Integracao::findByID($id);
if (!$integracao->exists()) {
    $msg = 'A integração não foi informada ou não existe';
    json($msg);
}
$old_integracao = $integracao;
try {
    $integracao = new Integracao($_POST);
    $integracao->filter($old_integracao);
    $integracao->save(array_keys($_POST));
    $old_integracao->clean($integracao);
    $msg = sprintf(
        'Integração "%s" atualizada com sucesso!',
        $integracao->getNome()
    );
    json(null, ['item' => $integracao->publish(), 'msg' => $msg]);
} catch (\Exception $e) {
    $integracao->clean($old_integracao);
    $errors = [];
    if ($e instanceof \MZ\Exception\ValidationException) {
        $errors = $e->getErrors();
    }
    json($e->getMessage(), null, ['errors' => $errors]);
}

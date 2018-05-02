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

use MZ\Employee\Funcao;

need_owner();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$funcao = Funcao::findByID($id);
if (!$funcao->exists()) {
    $msg = 'A função não foi informada ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/funcao/');
}
try {
    $funcao->delete();
    $funcao->clean(new Funcao());
    $msg = sprintf('Função "%s" excluída com sucesso!', $funcao->getDescricao());
    if (is_output('json')) {
        json('msg', $msg);
    }
    \Thunder::success($msg, true);
} catch (\Exception $e) {
    $msg = sprintf(
        'Não foi possível excluir a função "%s"',
        $funcao->getDescricao()
    );
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::error($msg);
}
redirect('/gerenciar/funcao/');

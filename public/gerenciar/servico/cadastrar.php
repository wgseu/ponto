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

use MZ\Product\Servico;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROSERVICOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$servico = Servico::findByID($id);
$servico->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_servico = $servico;
if (is_post()) {
    $servico = new Servico($_POST);
    try {
        $servico->filter($old_servico);
        $servico->insert();
        $old_servico->clean($servico);
        $msg = sprintf(
            'Serviço "%s" cadastrada com sucesso!',
            $servico->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $servico->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/servico/');
    } catch (\Exception $e) {
        $servico->clean($old_servico);
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
} else {
    $servico->setAtivo('Y');
}
$app->getResponse('html')->output('gerenciar_servico_cadastrar');

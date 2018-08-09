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
use MZ\Wallet\Carteira;
use MZ\Payment\FormaPagto;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROFORMASPAGTO, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$forma_pagto = FormaPagto::findByID($id);
if (!$forma_pagto->exists()) {
    $msg = 'A forma de pagamento não foi informada ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/forma_pagto/');
}
$focusctrl = 'descricao';
$errors = [];
$old_forma_pagto = $forma_pagto;
if (is_post()) {
    $forma_pagto = new FormaPagto($_POST);
    try {
        $forma_pagto->filter($old_forma_pagto);
        $forma_pagto->update();
        $old_forma_pagto->clean($forma_pagto);
        $msg = sprintf(
            'Forma de pagamento "%s" atualizada com sucesso!',
            $forma_pagto->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $forma_pagto->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/forma_pagto/');
    } catch (\Exception $e) {
        $forma_pagto->clean($old_forma_pagto);
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
$_carteiras = Carteira::findAll();
return $app->getResponse()->output('gerenciar_forma_pagto_editar');

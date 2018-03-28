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

use MZ\Wallet\Carteira;

need_permission(Permissao::NOME_CADASTROFORMASPAGTO);
$focusctrl = 'descricao';
$errors = [];
$old_forma_pagto = $forma_pagto;
if (is_post()) {
    $forma_pagto = new FormaPagto($_POST);
    try {
        $forma_pagto->setID(null);
        $forma_pagto->setMinParcelas(numberval($forma_pagto->getMinParcelas()));
        $forma_pagto->setMaxParcelas(numberval($forma_pagto->getMaxParcelas()));
        $forma_pagto->setParcelasSemJuros(numberval($forma_pagto->getParcelasSemJuros()));
        $forma_pagto->setJuros(moneyval($forma_pagto->getJuros()));
        $forma_pagto->filter($old_forma_pagto);
        $forma_pagto->insert();
        $old_forma_pagto->clean($forma_pagto);
        $msg = sprintf(
            'Forma de pagamento "%s" cadastrada com sucesso!',
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
} else {
    $forma_pagto = new FormaPagto();
    $forma_pagto->setAtiva('Y');
}
$_carteiras = Carteira::findAll();
include template('gerenciar_forma_pagto_cadastrar');

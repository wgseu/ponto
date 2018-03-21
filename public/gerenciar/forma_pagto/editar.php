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

need_permission(PermissaoNome::CADASTROFORMASPAGTO);
$forma_pagto = ZFormaPagto::getPeloID($_GET['id']);
if (is_null($forma_pagto->getID())) {
    Thunder::warning('A forma de pagamento de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/forma_pagto/');
}
$focusctrl = 'descricao';
$errors = [];
$old_forma_pagto = $forma_pagto;
if (is_post()) {
    $forma_pagto = new ZFormaPagto($_POST);
    try {
        $forma_pagto->setID($old_forma_pagto->getID());
        $forma_pagto->setMinParcelas(numberval($forma_pagto->getMinParcelas()));
        $forma_pagto->setMaxParcelas(numberval($forma_pagto->getMaxParcelas()));
        $forma_pagto->setParcelasSemJuros(numberval($forma_pagto->getParcelasSemJuros()));
        $forma_pagto->setJuros(moneyval($forma_pagto->getJuros()));
        $forma_pagto = ZFormaPagto::atualizar($forma_pagto);
        Thunder::success('Forma de pagamento "'.$forma_pagto->getDescricao().'" atualizada com sucesso!', true);
        redirect('/gerenciar/forma_pagto/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
}
$_carteiras = Carteira::findAll();
include template('gerenciar_forma_pagto_editar');

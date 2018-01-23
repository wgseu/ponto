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

need_permission(PermissaoNome::CADASTROCARTEIRAS);
$carteira = Carteira::findByID($_GET['id']);
if (is_null($carteira->getID())) {
    Thunder::warning('A carteira de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/carteira/');
}
$focusctrl = 'descricao';
$errors = array();
$old_carteira = $carteira;
if (is_post()) {
    $carteira = new Carteira($_POST);
    try {
        $carteira->filter($old_carteira);
        $carteira->update();
        Thunder::success('Carteira "'.$carteira->getDescricao().'" atualizada com sucesso!', true);
        redirect('/gerenciar/carteira/');
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
$_banco = $carteira->findBancoID();
include template('gerenciar_carteira_editar');

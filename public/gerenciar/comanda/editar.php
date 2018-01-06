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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::CADASTROCOMANDAS);
$comanda = \MZ\Sale\Comanda::findByID($_GET['id']);
if (is_null($comanda->getID())) {
    Thunder::warning('A comanda de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/comanda/');
}
$focusctrl = 'nome';
$errors = array();
$old_comanda = $comanda;
if ($_POST) {
    $comanda = new \MZ\Sale\Comanda($_POST);
    try {
        $comanda->setID($old_comanda->getID());
        $comanda = \MZ\Sale\Comanda::atualizar($comanda);
        Thunder::success('Comanda "'.$comanda->getNome().'" atualizada com sucesso!', true);
        redirect('/gerenciar/comanda/');
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
include template('gerenciar_comanda_editar');

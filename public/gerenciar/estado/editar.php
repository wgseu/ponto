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

need_permission(PermissaoNome::CADASTROESTADOS);
$estado = ZEstado::getPeloID($_GET['id']);
if (is_null($estado->getID())) {
    Thunder::warning('O estado de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/estado/');
}
$focusctrl = 'nome';
$errors = array();
$old_estado = $estado;
if (is_post()) {
    $estado = new ZEstado($_POST);
    try {
        $estado->setID($old_estado->getID());
        $estado = ZEstado::atualizar($estado);
        Thunder::success('Estado "'.$estado->getNome().'" atualizado com sucesso!', true);
        redirect('/gerenciar/estado/');
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
$_paises = ZPais::getTodas();
include template('gerenciar_estado_editar');

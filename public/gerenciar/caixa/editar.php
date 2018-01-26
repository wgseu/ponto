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

need_permission(PermissaoNome::CADASTROCAIXAS);
$caixa = ZCaixa::getPeloID($_GET['id']);
if (is_null($caixa->getID())) {
    Thunder::warning('O caixa de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/caixa/');
}
$focusctrl = 'descricao';
$errors = array();
$old_caixa = $caixa;
if (is_post()) {
    $caixa = new ZCaixa($_POST);
    try {
        $caixa->setID($old_caixa->getID());
        if (!$__sistema__->isFiscalVisible()) {
            $caixa->setNumeroInicial($old_caixa->getNumeroInicial());
            $caixa->setSerie($old_caixa->getSerie());
        }
        $caixa = ZCaixa::atualizar($caixa);
        Thunder::success('Caixa "'.$caixa->getDescricao().'" atualizado com sucesso!', true);
        redirect('/gerenciar/caixa/');
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
include template('gerenciar_caixa_editar');

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

need_permission(PermissaoNome::CADASTROSERVICOS);
$servico = ZServico::getPeloID($_GET['id']);
if (is_null($servico->getID())) {
    Thunder::warning('A serviço de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/servico/');
}
$focusctrl = 'descricao';
$errors = array();
$old_servico = $servico;
if ($_POST) {
    $servico = new ZServico($_POST);
    try {
        $servico->setID($old_servico->getID());
        $_data_inicio = date_create_from_format('d/m/Y H:i', $servico->getDataInicio());
        $servico->setDataInicio($_data_inicio===false?null:date_format($_data_inicio, 'Y-m-d H:i:s'));
        $_data_fim = date_create_from_format('d/m/Y H:i', $servico->getDataFim());
        $servico->setDataFim($_data_fim===false?null:date_format($_data_fim, 'Y-m-d H:i:s'));
        $servico->setValor(moneyval($servico->getValor()));
        $servico = ZServico::atualizar($servico);
        Thunder::success('Serviço "'.$servico->getDescricao().'" atualizada com sucesso!', true);
        redirect('/gerenciar/servico/');
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
include template('gerenciar_servico_editar');

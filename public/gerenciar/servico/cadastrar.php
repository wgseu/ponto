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

use MZ\__TODO_NAMESPACE__\Servico;

need_permission(\Permissao::NOME_CADASTROSERVICOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$servico = Servico::findByID($id);
$servico->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_servico = $servico;
if (is_post()) {
    $servico = new Servico($_POST);
    try {
        $servico->setID(null);
        $_data_inicio = date_create_from_format('d/m/Y H:i', $servico->getDataInicio());
        $servico->setDataInicio($_data_inicio===false?null:date_format($_data_inicio, 'Y-m-d H:i:00'));
        $_data_fim = date_create_from_format('d/m/Y H:i', $servico->getDataFim());
        $servico->setDataFim($_data_fim===false?null:date_format($_data_fim, 'Y-m-d H:i:00'));
        $servico->setValor(moneyval($servico->getValor()));
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
    $servico = new Servico();
    $servico->setDataInicio(date('Y-m-d H:i:s', time()));
    $servico->setDataFim(date('Y-m-d H:i:s', time()));
    $servico->setAtivo('Y');
}
include template('gerenciar_servico_cadastrar');

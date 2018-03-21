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

need_permission(PermissaoNome::CADASTROCONTAS);
$conta = ZConta::getPeloID($_GET['id']);
if (is_null($conta->getID())) {
    Thunder::warning('A conta de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/conta/');
}
$focusctrl = 'descricao';
$errors = [];
$old_conta = $conta;
if (is_post()) {
    $conta = new ZConta($_POST);
    try {
        // não deixa alterar esses dados
        $conta->setID($old_conta->getID());
        $conta->setFuncionarioID($old_conta->getFuncionarioID());
        $conta->setPedidoID($old_conta->getPedidoID());
        $conta->setCancelada($old_conta->getCancelada());

        $conta->setValor(abs(moneyval($conta->getValor())));
        $conta->setAcrescimo(abs(moneyval($conta->getAcrescimo())));
        $conta->setMulta(abs(moneyval($conta->getMulta())));
        if (intval($_POST['tipo']) < 0) {
            $conta->setValor(-$conta->getValor());
            $conta->setAcrescimo(-$conta->getAcrescimo());
            $conta->setMulta(-$conta->getMulta());
        }
        $conta->setJuros(moneyval($conta->getJuros()) / 100.0);
        $_vencimento = date_create_from_format('d/m/Y', $conta->getVencimento());
        $conta->setVencimento($_vencimento===false?null:date_format($_vencimento, 'Y-m-d'));
        $_data_emissao = date_create_from_format('d/m/Y', $conta->getDataEmissao());
        $conta->setDataEmissao($_data_emissao===false?null:date_format($_data_emissao, 'Y-m-d'));
        $_data_pagamento = date_create_from_format('d/m/Y', $conta->getDataPagamento());
        $conta->setDataPagamento($_data_pagamento===false?null:date_format($_data_pagamento, 'Y-m-d'));
        $anexocaminho = upload_document('raw_anexocaminho', 'conta');
        if (!is_null($anexocaminho)) {
            $conta->setAnexoCaminho($anexocaminho);
        } elseif (trim($conta->getAnexoCaminho()) != '') { // evita sobrescrever
            $conta->setAnexoCaminho($old_conta->getAnexoCaminho());
        }
        $conta = ZConta::atualizar($conta);
        // exclui o documento antigo
        if (!is_null($old_conta->getAnexoCaminho()) &&
            $conta->getAnexoCaminho() != $old_conta->getAnexoCaminho() && !is_local_path($old_conta->getAnexoCaminho())) {
            unlink(WWW_ROOT . get_document_url($old_conta->getAnexoCaminho(), 'conta'));
        }
        Thunder::success('Conta "'.$conta->getDescricao().'" atualizada com sucesso!', true);
        redirect('/gerenciar/conta/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // exclui o documento enviado
    if (!is_null($conta->getAnexoCaminho()) &&
        $old_conta->getAnexoCaminho() != $conta->getAnexoCaminho()) {
        unlink(WWW_ROOT . get_document_url($conta->getAnexoCaminho(), 'conta'));
    }
    $conta->setAnexoCaminho($old_conta->getAnexoCaminho());
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
}

$classificacao_id_obj = \ZClassificacao::getPeloID($conta->getClassificacaoID());
$sub_classificacao_id_obj = \ZClassificacao::getPeloID($conta->getSubClassificacaoID());
include template('gerenciar_conta_editar');

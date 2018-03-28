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

use MZ\__TODO_NAMESPACE__\Patrimonio;

need_permission(\Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$patrimonio = Patrimonio::findByID($id);
if (!$patrimonio->exists()) {
    $msg = 'O patrimônio informado não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/patrimonio/');
}
$focusctrl = 'descricao';
$errors = [];
$old_patrimonio = $patrimonio;
if (is_post()) {
    $patrimonio = new Patrimonio($_POST);
    try {
        $patrimonio->setID($old_patrimonio->getID());
        $patrimonio->setQuantidade(moneyval($patrimonio->getQuantidade()));
        $patrimonio->setAltura(moneyval($patrimonio->getAltura()));
        $patrimonio->setLargura(moneyval($patrimonio->getLargura()));
        $patrimonio->setComprimento(moneyval($patrimonio->getComprimento()));
        $patrimonio->setCusto(moneyval($patrimonio->getCusto()));
        $patrimonio->setValor(moneyval($patrimonio->getValor()));
        $patrimonio->setDataAtualizacao(date('Y-m-d H:i:s', time()));
        $imagem_anexada = upload_image('raw_imagemanexada', 'patrimonio');
        if (!is_null($imagem_anexada)) {
            $patrimonio->setImagemAnexada($imagem_anexada);
        } elseif (trim($patrimonio->getImagemAnexada()) != '') { // evita sobrescrever
            $patrimonio->setImagemAnexada($old_patrimonio->getImagemAnexada());
        }
        $patrimonio->filter($old_patrimonio);
        $patrimonio->update();
        $old_patrimonio->clean($patrimonio);
        // exclui a foto do bem antiga
        if (!is_null($old_patrimonio->getImagemAnexada()) &&
            $patrimonio->getImagemAnexada() != $old_patrimonio->getImagemAnexada() &&
            !is_local_path($old_patrimonio->getImagemAnexada())) {
            unlink(WWW_ROOT . get_image_url($old_patrimonio->getImagemAnexada(), 'patrimonio'));
        }
        $msg = sprintf(
            'Patrimônio "%s" atualizado com sucesso!',
            $patrimonio->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $patrimonio->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/patrimonio/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // exclui a foto do bem enviada
    if (!is_null($patrimonio->getImagemAnexada()) &&
        $old_patrimonio->getImagemAnexada() != $patrimonio->getImagemAnexada()) {
        unlink(WWW_ROOT . get_image_url($patrimonio->getImagemAnexada(), 'patrimonio'));
    }
    $patrimonio->setImagemAnexada($old_patrimonio->getImagemAnexada());
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        \Thunder::error($value);
        break;
    }
}
if ($focusctrl == 'empresaid') {
    $focusctrl == 'empresa';
} elseif ($focusctrl == 'fornecedorid') {
    $focusctrl == 'fornecedor';
}
include template('gerenciar_patrimonio_editar');

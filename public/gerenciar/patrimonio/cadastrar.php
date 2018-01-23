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

need_permission(PermissaoNome::CADASTROPATRIMONIO);
$focusctrl = 'descricao';
$errors = array();
if (is_post()) {
    $patrimonio = new ZPatrimonio($_POST);
    try {
        $patrimonio->setID(null);
        $patrimonio->setQuantidade(moneyval($patrimonio->getQuantidade()));
        $patrimonio->setAltura(moneyval($patrimonio->getAltura()));
        $patrimonio->setLargura(moneyval($patrimonio->getLargura()));
        $patrimonio->setComprimento(moneyval($patrimonio->getComprimento()));
        $patrimonio->setCusto(moneyval($patrimonio->getCusto()));
        $patrimonio->setValor(moneyval($patrimonio->getValor()));
        $patrimonio->setDataAtualizacao(date('Y-m-d H:i:s', time()));
        $imagem_anexada = upload_image('raw_imagemanexada', 'patrimonio');
        $patrimonio->setImagemAnexada($imagem_anexada);
        $patrimonio = ZPatrimonio::cadastrar($patrimonio);
        Thunder::success('Patrimônio "'.$patrimonio->getDescricao().'" cadastrado com sucesso!', true);
        redirect('/gerenciar/patrimonio/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // remove a foto do bem enviada
    if (!is_null($patrimonio->getImagemAnexada())) {
        unlink(WWW_ROOT . get_image_url($patrimonio->getImagemAnexada(), 'patrimonio'));
    }
    $patrimonio->setImagemAnexada(null);
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
} else {
    $patrimonio = new ZPatrimonio();
    $patrimonio->setAtivo('Y');
}
if ($focusctrl == 'empresaid') {
    $focusctrl == 'empresa';
} elseif ($focusctrl == 'fornecedorid') {
    $focusctrl == 'fornecedor';
}
include template('gerenciar_patrimonio_cadastrar');

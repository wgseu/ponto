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

need_permission(PermissaoNome::CADASTROPRODUTOS);
$focusctrl = 'descricao';
$errors = array();
if ($_POST) {
	$categoria = new ZCategoria($_POST);
	try {
		$categoria->setID(null);
		$imagem = upload_image('raw_imagem', 'categoria', null, 256, 256, true);
		if(!is_null($imagem)) {
			$categoria->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'categoria')));
			unlink(WWW_ROOT . get_image_url($imagem, 'categoria'));
		} else
			$categoria->setImagem(null);
		$categoria->setDataAtualizacao(date('Y-m-d H:i:s', time()));
		$categoria = ZCategoria::cadastrar($categoria);
		Thunder::success('Categoria "'.$categoria->getDescricao().'" cadastrada com sucesso!', true);
		redirect('/gerenciar/categoria/');
	} catch (ValidationException $e) {
		$errors = $e->getErrors();
	} catch (Exception $e) {
		$errors['unknow'] = $e->getMessage();
	}
	// remove a imagem enviada
	$categoria->setImagem(null);
	foreach($errors as $key => $value) {
		$focusctrl = $key;
		Thunder::error($value);
		break;
	}
} else {
	$categoria = new ZCategoria();
	$categoria->setDataAtualizacao(date('Y-m-d H:i:s', time()));
}
$_categorias = ZCategoria::getTodas(true, true);
include template('gerenciar_categoria_cadastrar');
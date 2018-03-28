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

use MZ\__TODO_NAMESPACE__\Categoria;

need_permission(\Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$categoria = Categoria::findByID($id);
if (!$categoria->exists()) {
    $msg = 'A categoria informada não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/categoria/');
}
$focusctrl = 'descricao';
$errors = [];
$old_categoria = $categoria;
if (is_post()) {
    $categoria = new Categoria($_POST);
    try {
        $categoria->setID($old_categoria->getID());
        $imagem = upload_image('raw_imagem', 'categoria', null, 256, 256, true);
        if (!is_null($imagem)) {
            $categoria->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'categoria')));
            unlink(WWW_ROOT . get_image_url($imagem, 'categoria'));
        } elseif (trim($categoria->getImagem()) != '') { // evita sobrescrever
            $categoria->setImagem(true);
        }
        $categoria->setDataAtualizacao(date('Y-m-d H:i:s', time()));
        $categoria->filter($old_categoria);
        $categoria->update();
        $old_categoria->clean($categoria);
        $msg = sprintf(
            'Categoria "%s" atualizada com sucesso!',
            $categoria->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $categoria->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/categoria/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // restaura a imagem original
    $categoria->setImagem($old_categoria->getImagem());
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        \Thunder::error($value);
        break;
    }
}
$_categorias = Categoria::getTodas(true, true);
include template('gerenciar_categoria_editar');

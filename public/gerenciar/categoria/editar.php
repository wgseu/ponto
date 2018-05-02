<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
require_once(dirname(__DIR__) . '/app.php');

use MZ\Product\Categoria;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
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
    } catch (\Exception $e) {
        $categoria->clean($old_categoria);
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
}
$_categorias = Categoria::findAll(['categoriaid' => null]);
$app->getResponse('html')->output('gerenciar_categoria_editar');

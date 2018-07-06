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

use MZ\Product\Produto;
use MZ\Product\Categoria;
use MZ\Product\Unidade;
use MZ\Environment\Setor;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$produto = Produto::find(['id' => $id, 'promocao' => 'N']);
if (!$produto->exists()) {
    $msg = 'O produto não foi informado ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/produto/');
}
$focusctrl = 'descricao';
$errors = [];
$old_produto = $produto;
if (is_post()) {
    $produto = new Produto($_POST);
    try {
        $produto->filter($old_produto);
        $produto->update();
        $old_produto->clean($produto);
        $produto->load(['id' => $produto->getID(), 'promocao' => 'N']);
        $msg = sprintf(
            'Produto "%s" atualizado com sucesso!',
            $produto->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $produto->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/produto/');
    } catch (\Exception $e) {
        $produto->clean($old_produto);
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
}
$_categorias = Categoria::findAll();
$_unidades = Unidade::findAll();
$_setores = Setor::findAll();
$app->getResponse('html')->output('gerenciar_produto_editar');

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

$pagetitle = 'Produtos';
$categorias = Categoria::findAll(['disponivel' => 'Y'], ['vendas' => -1]);
if (count($categorias) > 0) {
    $categoria_atual = current($categorias);
    $negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
    $condition = [
        'categoriaid' => $categoria_atual->getID(),
        'visivel' => 'Y',
        'permitido' => 'Y',
        'promocao' => 'Y'
    ];
    if (!$negativo) {
        $condition['disponivel'] = 'Y';
    }
    $produtos = Produto::findAll($condition);
} else {
    $produtos = [];
    $categoria_atual = new Categoria();
}
$app->getResponse('html')->output('produto_index');

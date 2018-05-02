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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\Product\Produto;

$limit = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
if ($limit < 1 || $limit > 10) {
    $limit = 5;
}
$primeiro = isset($_GET['primeiro']) ? $_GET['primeiro'] : null;
if ($primeiro) {
    $limit = 1;
}
$condition = [
    'promocao' => 'Y'
];
if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $limit = null;
    $condition['categoria'] = intval($_GET['categoria']);
}
if (isset($_GET['busca'])) {
    $condition['search'] = $_GET['busca'];
}
$estoque = isset($_GET['estoque']) ? intval($_GET['estoque']) : 0;
$negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
if ($estoque > 0) {
    $condition['permitido'] = 'Y';
    $condition['visivel'] = 'Y';
    $condition['tipo'] = Produto::TIPO_PRODUTO;
} elseif ($estoque < 0 && is_manager()) {
} else {
    if (!$negativo) {
        $condition['disponivel'] = 'Y';
    }
    $condition['permitido'] = 'Y';
    $condition['visivel'] = 'Y';
}
$produtos = Produto::rawFindAll($condition, [], $limit);

$response = ['status' => 'ok'];
$campos = [
    'id',
    'categoriaid',
    'descricao',
    'detalhes',
    'precovenda',
    'tipo',
    'conteudo',
    'divisivel',
    'dataatualizacao',
    // extras
    'estrelas',
    'estoque',
    'imagemurl',
    'categoria',
    'unidade',
];
$_produtos = [];
foreach ($produtos as $produto) {
    // TODO implementar estrelas de mais vendido
    $produto['estrelas'] = 3;
    $produto['imagemurl'] = get_image_url($produto['imagem'], 'produto', null);
    $_produtos[] = array_intersect_key($produto, array_flip($campos));
}
$response['produtos'] = $_produtos;
json($response);

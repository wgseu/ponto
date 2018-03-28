<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(dirname(__DIR__)) . '/app.php');

$estoque = intval($_GET['estoque']);
if ($estoque < 0 && is_manager()) {
    $estoque = null;
}
$limit = isset($_GET['limite'])?intval($_GET['limite']):5;
if ($limit < 1 || $limit > 10) {
    $limit = 5;
}
if ($_GET['primeiro']) {
    $limit = 1;
}
$categoria_id = null;
if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $limit = null;
    $categoria_id = intval($_GET['categoria']);
}
$produtos = Produto::getTodos(
    $_GET['busca'],
    $categoria_id,
    null, // unidade_id
    null, // tipo
    $estoque,
    null, // setor de estoque
    null, // incluir promoção
    null, // visibilidade
    null, // mostrar com estoque limitado
    null, // pesável
    true, // raw mode
    0, // offset
    $limit
);
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

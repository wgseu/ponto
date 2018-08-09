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
use MZ\Product\Pacote;

$grupo_id = isset($_GET['grupo']) ? intval($_GET['grupo']) : null;
if (is_null($grupo_id)) {
    json('Grupo não informado');
}
$limite = isset($_GET['limite']) ? intval($_GET['limite']): null;
if (!is_null($limite) && $limite < 1) {
    $limite = null;
}
$busca = isset($_GET['busca']) ? strval($_GET['busca']) : null;
$associacoes = isset($_POST['pacote']) ? $_POST['pacote']: [];
$condition = [
    'grupoid' => $grupo_id,
    'visivel' => 'Y',
    'search' => $busca
];
if (is_array($associacoes) && count($associacoes) > 0) {
    $condition['associacaoid'] = $associacoes;
}
$pacotes = Pacote::rawFindAll($condition, [], $limite);
$items = [];
foreach ($pacotes as $item) {
    $folder = is_null($item['produtoid']) ? 'propriedade': 'produto';
    $item['quantidade'] = 1;
    $item['imagemurl'] = get_image_url($item['imagemurl'], $folder, null);
    $items[] = $item;
}
json('pacotes', $items);

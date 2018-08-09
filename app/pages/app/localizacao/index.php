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
use MZ\Location\Localizacao;
use MZ\Location\Estado;
use MZ\Location\Cidade;
use MZ\Util\Filter;

$estado_id = isset($_GET['estadoid']) ? $_GET['estadoid'] : null;
$estado = Estado::findByID($estado_id);
if (!$estado->exists()) {
    json('O estado não foi informado ou não existe!');
}
$cidade = Cidade::findByEstadoIDNome($estado_id, isset($_GET['cidade'])?trim($_GET['cidade']):null);
if (!$cidade->exists()) {
    json('A cidade informada não existe!');
}
$condition = Filter::query($_GET);
$condition['cidadeid'] = $cidade->getID();
// filter remove empty entry
if (array_key_exists('typesearch', $_GET)) {
    $condition['typesearch'] = $_GET['typesearch'];
}
$localizacoes = Localizacao::findAll($condition, [], 10);
$campos = [
    'cep',
    'logradouro'
];
if (isset($_GET['tipo']) && $_GET['tipo'] == Localizacao::TIPO_APARTAMENTO) {
    $campos[] = 'numero';
    $campos[] = 'condominio';
}
$items = [];
foreach ($localizacoes as $localizacao) {
    $bairro = $localizacao->findBairroID();
    $item = $localizacao->publish();
    $item = array_intersect_key($item, array_flip($campos));
    $item['bairro'] = $bairro->getNome();
    $items[] = $item;
}
json('items', $items);

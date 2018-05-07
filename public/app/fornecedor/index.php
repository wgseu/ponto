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

use MZ\Stock\Fornecedor;

need_manager(true);

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
$primeiro = isset($_GET['primeiro']) ? $_GET['primeiro'] : null;
$search = isset($_GET['busca']) ? $_GET['busca'] : null;
if ($primeiro || check_fone($search, true)) {
    $limite = 1;
} elseif ($limite < 1) {
    $limite = 5;
} elseif ($limite > 20) {
    $limite = 20;
}
$condition = [];
if (isset($_GET['busca'])) {
    $condition['search'] = $search;
}
$fornecedores = Fornecedor::findAll($condition, [], $limite);
$items = [];
$domask = isset($_GET['format']) ? intval($_GET['format']) != 0: false;
foreach ($fornecedores as $fornecedor) {
    $cliente = $fornecedor->findEmpresaID();
    $cliente_item = $cliente->publish();
    $item = $fornecedor->publish();
    $item['nome'] = $cliente->getNome();
    $item['fone1'] = $cliente->getFone(1);
    $item['cnpj'] = $cliente->getCPF();
    if ($domask) {
        $item['fone1'] = $cliente_item['fone1'];
        $item['cnpj'] = $cliente_item['cpf'];
    }
    $item['email'] = $cliente->getEmail();
    $item['imagemurl'] = $cliente_item['imagem'];
    $items[] = $item;
}
json('items', $items);

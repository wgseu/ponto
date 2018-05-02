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

use MZ\Account\Cliente;
use MZ\Util\Mask;

need_manager(true);

$limit = intval(isset($_GET['limite'])?$_GET['limite']:5);
if ((isset($_GET['primeiro'])?$_GET['primeiro']:false) ||
    check_fone(isset($_GET['busca']) ? $_GET['busca'] : null, true)
) {
    $limit = 1;
} elseif ($limit < 1) {
    $limit = 5;
} elseif ($limit > 20) {
    $limit = 20;
}
$condition = [];
if (isset($_GET['busca'])) {
    $condition['search'] = $_GET['busca'];
}
if (isset($_GET['tipo'])) {
    $condition['tipo'] = $_GET['tipo'];
}
$clientes = Cliente::findAll($condition, [], $limit);
$response = ['status' => 'ok'];
$campos = [
    'id',
    'tipo',
    'genero',
    'nome',
    'sobrenome',
    'fone1',
    'email',
    'cpf',
    'imagemurl',
];
$_clientes = [];
$domask = intval(isset($_GET['formatar']) ? $_GET['formatar'] : 0) != 0;
foreach ($clientes as $cliente) {
    $_cliente = $cliente->publish();
    if ($domask) {
        $_cliente['fone1'] = Mask::phone($cliente->getFone(1));
    }
    $_cliente['imagemurl'] = $_cliente['imagem'];
    $_clientes[] = array_intersect_key($_cliente, array_flip($campos));
}
$response['clientes'] = $_clientes;
json($response);

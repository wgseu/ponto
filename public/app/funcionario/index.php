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

use MZ\Employee\Funcionario;
use MZ\Util\Filter;
use MZ\Util\Mask;

need_manager(true);

$limit = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
$search = isset($_GET['search']) ? $_GET['search']: null;
if (check_fone($search, true)) {
    $limit = 1;
} elseif ($limit < 1) {
    $limit = 5;
} elseif ($limit > 20) {
    $limit = 20;
}
$condition = Filter::query($_GET);
$funcionarios = Funcionario::findAll($condition, [], $limit);
$response = ['status' => 'ok'];
$campos = [
    'id',
    'nome',
    'fone1',
    'cpf',
    'email',
    'funcao',
    'imagemurl',
];
$items = [];
foreach ($funcionarios as $funcionario) {
    $item = $funcionario->publish();
    $funcao = $funcionario->findFuncaoID();
    $cliente = $funcionario->findClienteID();
    $item['nome'] = $cliente->getNomeCompleto();
    $item['fone1'] = Mask::phone($cliente->getFone(1));
    $item['cpf'] = Mask::cpf($cliente->getCPF());
    $item['email'] = $cliente->getEmail();
    $item['funcao'] = $funcao->getDescricao();
    $item['imagemurl'] = get_image_url($cliente->getImagem(), 'cliente', null);
    $items[] = array_intersect_key($item, array_flip($campos));
}
$response['items'] = $items;
json($response);

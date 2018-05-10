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

use MZ\Employee\Funcionario;
use MZ\Employee\Funcao;
use MZ\Account\Cliente;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_manager();

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
if (!logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS)) {
    $condition['id'] = logged_employee()->getID();
}
$funcionario = new Funcionario($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Funcionario::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$funcionarios = Funcionario::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($funcionarios as $_funcionario) {
        $items[] = $_funcionario->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$funcao = $funcionario->findFuncaoID();
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;
if ($estado == 'ativo') {
    $estado = 'Y';
} elseif ($estado == 'inativo') {
    $estado = 'N';
} else {
    $estado = null;
}
$funcoes = [];
$_funcoes = Funcao::findAll();
foreach ($_funcoes as $funcao) {
    $funcoes[$funcao->getID()] = $funcao->getDescricao();
}
$generos = Cliente::getGeneroOptions();
$estados = [
    'Y' => 'Ativo',
    'N' => 'Inativo',
];
$app->getResponse('html')->output('gerenciar_funcionario_index');

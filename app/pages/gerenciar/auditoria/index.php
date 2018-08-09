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
use MZ\System\Auditoria;
use MZ\Util\Filter;
use MZ\System\Permissao;
use MZ\Employee\Funcionario;

need_permission(Permissao::NOME_RELATORIOAUDITORIA, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$auditoria = new Auditoria($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Auditoria::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$auditorias = Auditoria::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($auditorias as $_auditoria) {
        $items[] = $_auditoria->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$funcionarios = Funcionario::findAll();
$_funcionario_names = [];
foreach ($funcionarios as $funcionario) {
    $_cliente = $funcionario->findClienteID();
    $_funcionario_names[$funcionario->getID()] = $_cliente->getLogin();
}

$_funcionario = $auditoria->findFuncionarioID();
$_tipo_names = Auditoria::getTipoOptions();
$_prioridade_names = Auditoria::getPrioridadeOptions();
$_prioridade_classe = [
    'Baixa' => '',
    'Media' => 'warning',
    'Alta' => 'danger'
];
$_tipo_icon = [
    'Financeiro' => 0,
    'Administrativo' => 16
];
return $app->getResponse()->output('gerenciar_auditoria_index');

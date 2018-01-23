<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(__DIR__) . '/app.php');

need_permission(PermissaoNome::RELATORIOAUDITORIA);

$count = ZAuditoria::getCount($_GET['query'], $_GET['funcionarioid'], $_GET['tipo'], $_GET['prioridade']);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$auditorias = ZAuditoria::getTodas($_GET['query'], $_GET['funcionarioid'], $_GET['tipo'], $_GET['prioridade'], $offset, $pagesize);

$funcionarios = ZFuncionario::getTodos();
$_funcionario_names = array();
foreach ($funcionarios as $funcionario) {
    $_cliente = ZCliente::getPeloID($funcionario->getClienteID());
    $_funcionario_names[$funcionario->getID()] = $_cliente->getLogin();
}

$_tipo_names = array(
    'Financeiro' => 'Financeiro',
    'Administrativo' => 'Administrativo'
);
$_prioridade_names = array(
    'Baixa' => 'Baixa',
    'Media' => 'Média',
    'Alta' => 'Alta'
);

$_prioridade_classe = array(
    'Baixa' => '',
    'Media' => 'warning',
    'Alta' => 'danger'
);
$_tipo_icon = array(
    'Financeiro' => 0,
    'Administrativo' => 16
);
$_funcionario = ZFuncionario::getPeloID($_GET['funcionarioid']);
include template('gerenciar_auditoria_index');

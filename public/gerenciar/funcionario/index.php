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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager();
$funcao = ZFuncao::getPeloID($_GET['funcao']);
if ($_GET['estado'] == 'ativo') {
    $estado = 'Y';
} elseif ($_GET['estado'] == 'inativo') {
    $estado = 'N';
} else {
    $estado = null;
}
$funcoes = array();
if (have_permission(PermissaoNome::CADASTROFUNCIONARIOS)) {
    $count = ZFuncionario::getCount($_GET['query'], $funcao->getID(), $_GET['genero'], $estado);
    list($pagesize, $offset, $pagestring) = pagestring($count, 10);
    $funcionarios = ZFuncionario::getTodos($_GET['query'], $funcao->getID(), $_GET['genero'], $estado, $offset, $pagesize);
    $_funcoes = ZFuncao::getTodas();
    foreach ($_funcoes as $funcao) {
        $funcoes[$funcao->getID()] = $funcao->getDescricao();
    }
} else {
    $funcionarios = array();
    $funcionarios[] = $login_funcionario;
}
$generos = array(
    ClienteGenero::MASCULINO => 'Masculino',
    ClienteGenero::FEMININO => 'Feminino',
);
$estados = array(
    'ativo' => 'Ativo',
    'inativo' => 'Inativo',
);
$linguagens = get_languages_info();
include template('gerenciar_funcionario_index');

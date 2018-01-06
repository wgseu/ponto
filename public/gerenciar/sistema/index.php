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

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$tab_empresa = 'active';
$cliente = $__empresa__;
$localizacao = ZLocalizacao::getPeloClienteID($__empresa__->getID());
$bairro = ZBairro::getPeloID($localizacao->getBairroID());
$cidade = ZCidade::getPeloID($bairro->getCidadeID());
$estado = ZEstado::getPeloID($cidade->getEstadoID());
$pais_id = $estado->getPaisID();
$_estados = ZEstado::getTodosDaPaisID($pais_id);
$_paises = ZPais::getTodas();
include template('gerenciar_sistema_index');
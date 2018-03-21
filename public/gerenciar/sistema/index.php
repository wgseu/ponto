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

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$tab_empresa = 'active';
$cliente = $__empresa__;
if (is_null($cliente->getID())) {
	$cliente->setTipo(\ClienteTipo::JURIDICA);
}
$localizacao = \MZ\Location\Localizacao::find(['clienteid' => $__empresa__->getID()]);
$bairro = $localizacao->findBairroID();
$cidade = $bairro->findCidadeID();
$estado = $cidade->findEstadoID();
$_paises = \MZ\Location\Pais::findAll();
if (!$estado->exists() && count($_paises) > 0) {
	$estado->setPaisID(reset($_paises)->getID());
}
$pais_id = $estado->getPaisID();
$focusctrl = 'nome';
$_estados = \MZ\Location\Estado::findAll(['paisid' => $pais_id]);
include template('gerenciar_sistema_index');

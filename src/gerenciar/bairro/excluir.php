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

need_permission(PermissaoNome::CADASTROBAIRROS);
$id = $_GET['id'];
$bairro = ZBairro::getPeloID($id);
if (is_null($bairro->getID())) {
    Thunder::warning('O bairro de id "'.$id.'" não existe!');
    redirect('/gerenciar/bairro/');
}
try {
    ZBairro::excluir($id);
    Thunder::success('Bairro "' . $bairro->getNome() . '" excluído com sucesso!', true);
} catch (Exception $e) {
    Thunder::error('Não foi possível excluir o bairro "' . $bairro->getNome() . '"!');
}
redirect('/gerenciar/bairro/');

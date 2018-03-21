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

need_permission(PermissaoNome::ALTERARCONFIGURACOES, isset($_POST));

if (is_post()) {
    try {
        DB::BeginTransaction();
        $modulo = ZModulo::getPeloID($_POST['id']);
        if (is_null($modulo->getID())) {
            throw new Exception('O módulo informado não existe', 1);
        }
        $modulo->setHabilitado($_POST['marcado']);
        $modulo = ZModulo::atualizar($modulo);
        try {
            $appsync = new AppSync();
            $appsync->systemOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        DB::Commit();
        json(['status' => 'ok']);
    } catch (Exception $e) {
        DB::Rollback();
        json($e->getMessage());
    }
}

$count = ZModulo::getCount($_GET['query']);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$modulos = ZModulo::getTodos($_GET['query'], $offset, $pagesize);

include template('gerenciar_modulo_index');

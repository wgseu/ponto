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

need_permission(Permissao::NOME_ALTERARPAGINAS);
$focusctrl = 'nome';
$errors = [];
$nomes = get_pages_info();
$linguagens = get_languages_info();
if (is_post()) {
    $pagina = new Pagina($_POST);
    try {
        $pagina->setID(null);
        $pagina->setLinguagemID(numberval($pagina->getLinguagemID()));
        $pagina->filter($old_pagina);
        $pagina->insert();
        $old_pagina->clean($pagina);
        \Thunder::success('Página "'.$nomes[$pagina->getNome()] . ' - ' . $linguagens[$pagina->getLinguagemID()].'" cadastrada com sucesso!', true);
        redirect('/gerenciar/pagina/');
    } catch (\Exception $e) {
        $pagina->clean($old_pagina);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        if (is_output('json')) {
            json($e->getMessage(), null, ['errors' => $errors]);
        }
        \Thunder::error($e->getMessage());
        foreach ($errors as $key => $value) {
            $focusctrl = $key;
            break;
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
} else {
    $pagina = new Pagina();
}
include template('gerenciar_pagina_cadastrar');

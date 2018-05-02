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

use MZ\System\Pagina;
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARPAGINAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$pagina = Pagina::findByID($id);
$pagina->setID(null);

$focusctrl = 'nome';
$errors = [];
$old_pagina = $pagina;
$nomes = Pagina::getNomeOptions();
$linguagens = get_languages_info();
if (is_post()) {
    $pagina = new Pagina($_POST);
    try {
        $pagina->filter($old_pagina);
        $pagina->insert();
        $old_pagina->clean($pagina);
        $msg = sprintf(
            'Página "%s - %s" cadastrada com sucesso!',
            $nomes[$pagina->getNome()],
            $linguagens[$pagina->getLinguagemID()]
        );
        if (is_output('json')) {
            json(null, ['item' => $pagina->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
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
}
$app->getResponse('html')->output('gerenciar_pagina_cadastrar');

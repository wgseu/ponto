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
use MZ\Wallet\Moeda;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROMOEDAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$moeda = Moeda::findByID($id);
if (!$moeda->exists()) {
    $msg = 'A moeda não foi informada ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/moeda/');
}
$focusctrl = 'nome';
$errors = [];
$old_moeda = $moeda;
if (is_post()) {
    $moeda = new Moeda($_POST);
    try {
        $moeda->filter($old_moeda);
        $moeda->save();
        $old_moeda->clean($moeda);
        $msg = sprintf(
            'Moeda "%s" atualizada com sucesso!',
            $moeda->getNome()
        );
        if (is_output('json')) {
            json(null, ['item' => $moeda->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/moeda/');
    } catch (\Exception $e) {
        $moeda->clean($old_moeda);
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
return $app->getResponse()->output('gerenciar_moeda_editar');

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

use MZ\Session\Caixa;
use MZ\Wallet\Carteira;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$cartao = Cartao::findByID($id);
if (!$cartao->exists()) {
    $msg = 'O cartão informado não existe!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/cartao/');
}
$focusctrl = 'descricao';
$errors = [];
$old_cartao = $cartao;
if (is_post()) {
    $cartao = new Cartao($_POST);
    try {
        $cartao->filter($old_cartao);
        $cartao->update();
        $old_cartao->clean($cartao);
        $msg = sprintf(
            'Cartão "%s" atualizado com sucesso!',
            $cartao->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $cartao->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/cartao/');
    } catch (\Exception $e) {
        $cartao->clean($old_cartao);
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
$_carteiras = Carteira::findAll();
$_imagens = Cartao::getImages();
$app->getResponse('html')->output('gerenciar_cartao_editar');

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
use MZ\Wallet\Carteira;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROCARTEIRAS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$carteira = Carteira::findByID($id);
$carteira->setID(null);

$focusctrl = 'descricao';
$errors = [];
$carteira = new Carteira();
$old_carteira = $carteira;
if (is_post()) {
    $carteira = new Carteira($_POST);
    try {
        $carteira->filter($old_carteira);
        $carteira->insert();
        $old_carteira->clean($carteira);
        $msg = sprintf(
            'Carteira "%s" cadastrada com sucesso!',
            $carteira->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $carteira->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/carteira/');
    } catch (\Exception $e) {
        $carteira->clean($old_carteira);
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
    $carteira->setAtiva('Y');
}
$_banco = $carteira->findBancoID();
return $app->getResponse()->output('gerenciar_carteira_cadastrar');

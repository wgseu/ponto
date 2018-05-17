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

use MZ\Stock\Fornecedor;
use MZ\System\Permissao;
use MZ\Database\DB;

need_permission(Permissao::NOME_CADASTROFORNECEDORES, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$fornecedor = Fornecedor::findByID($id);
$fornecedor->setID(null);

$focusctrl = 'empresaid';
$errors = [];
$old_fornecedor = $fornecedor;
if (is_post()) {
    $fornecedor = new Fornecedor($_POST);
    try {
        $fornecedor->filter($old_fornecedor);
        $fornecedor->insert();
        $old_fornecedor->clean($fornecedor);
        $msg = sprintf(
            'Fornecedor "%s" cadastrado com sucesso!',
            $fornecedor->getEmpresaID()
        );
        if (is_output('json')) {
            json(null, ['item' => $fornecedor->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/fornecedor/');
    } catch (\Exception $e) {
        $fornecedor->clean($old_fornecedor);
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
    $fornecedor->setPrazoPagamento(30);
}
$empresa_id_obj = $fornecedor->findEmpresaID();
$app->getResponse('html')->output('gerenciar_fornecedor_cadastrar');

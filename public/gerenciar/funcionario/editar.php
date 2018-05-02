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

use MZ\Employee\Funcionario;
use MZ\System\Permissao;

need_manager();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$funcionario = Funcionario::findByID($id);
if (!$funcionario->exists()) {
    $msg = 'O funcionário não foi informado ou não existe';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/funcionario/');
}
if ((
        !logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
        !is_self($funcionario)
    ) ||
    (
        $funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
        !is_self($funcionario) &&
        !is_owner()
    )
) {
    $msg = 'Você não tem permissão para alterar as informações desse(a) funcionário(a)';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/funcionario/');
}
$cliente_func = $funcionario->findClienteID();
$focusctrl = 'clienteid';
$errors = [];
$old_funcionario = $funcionario;
if (is_post()) {
    $funcionario = new Funcionario($_POST);
    try {
        $funcionario->filter($old_funcionario);
        $funcionario->update();
        $old_funcionario->clean($funcionario);
        $msg = sprintf(
            'Funcionário(a) "%s" atualizado(a) com sucesso!',
            $cliente_func->getAssinatura()
        );
        if (is_output('json')) {
            json(null, ['item' => $cliente_func->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/funcionario/');
    } catch (\Exception $e) {
        $funcionario->clean($old_funcionario);
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
$_funcoes = Funcao::findAll();
$linguagens = get_languages_info();
$app->getResponse('html')->output('gerenciar_funcionario_editar');

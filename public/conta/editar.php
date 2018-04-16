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

need_login(is_output('json'));
$cliente = logged_user();

$tab_dados = 'selected';
$gerenciando = false;
$cadastrar_cliente = false;
$aceitar = 'true';

$focusctrl = 'nome';
$errors = [];
$old_cliente = $cliente;
if (is_post()) {
    $cliente = new Cliente($_POST);
    try {
        // não deixa o usuário alterar os dados abaixo
        $cliente->setEmail($old_cliente->getEmail());
        $cliente->setTipo($old_cliente->getTipo());
        $cliente->setAcionistaID($old_cliente->getAcionistaID());
        $cliente->setSlogan($old_cliente->getSlogan());

        $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
        $cliente->passwordMatch($senha);

        $cliente->filter($old_cliente);
        $cliente->update();
        $old_cliente->clean($cliente);
        $msg = 'Conta atualizada com sucesso!';
        if (is_output('json')) {
            json(null, ['item' => $cliente->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/conta/editar');
    } catch (\Exception $e) {
        $cliente->clean($old_cliente);
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
        if ($focusctrl == 'genero') {
            $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}

$pagetitle = 'Editar Conta';
$app->getResponse('html')->output('conta_editar');

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
use MZ\Account\Cliente;
use MZ\Account\Authentication;
use MZ\System\Permissao;

if (is_login()) {
    $msg = 'Você já está cadastrado e autenticado!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::information($msg, true);
    redirect('/');
}
$cliente = new Cliente();
$errors = [];
$focusctrl = 'nome';
$old_cliente = $cliente;
$aceitar = null;
if (is_post()) {
    $cliente = new Cliente($_POST);
    $aceitar = isset($_POST['aceitar']) ? $_POST['aceitar'] : null;
    try {
        if ($aceitar != 'true') {
            throw new \MZ\Exception\ValidationException(
                ['aceitar' => 'Os termos não foram aceitos']
            );
        }
        $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
        $cliente->passwordMatch($senha);
        $cliente->filter($old_cliente);
        $cliente->insert();
        $old_cliente->clean($cliente);
        if (is_output('json')) {
            json('item', $cliente->publish());
        }
        $app->getAuthentication()->login($cliente);
        redirect(get_redirect_page());
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
            $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}

$pagetitle = 'Cadastrar';
return $app->getResponse()->output('conta_cadastrar');

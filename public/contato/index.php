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

$focusctrl = 'nome';
if (is_login()) {
    if (is_null(logged_user()->getEmail())) {
        $focusctrl = 'email';
    } else {
        $focusctrl = 'assunto';
    }
}
$erro = [];
if (is_post()) {
    $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : null;
    $nome = isset($_POST['nome']) ? strip_tags(trim($_POST['nome'])) : null;
    if (is_login()) {
        $email = $email ?: logged_user()->getEmail();
        $nome = $nome ?: logged_user()->getNome();
    }
    $assunto = isset($_POST['assunto']) ? strip_tags(trim($_POST['assunto'])) : null;
    $mensagem = isset($_POST['mensagem']) ? strip_tags(trim($_POST['mensagem'])) : null;
    if ($nome == '') {
        $erros['nome'] = 'O nome não pode ser vazio';
    }
    if (!check_email($email)) {
        $erros['email'] = 'O E-mail é inválido';
    }
    if ($assunto == '') {
        $erros['assunto'] = 'O assunto não foi informado';
    }
    if ($mensagem == '') {
        $erros['mensagem'] = 'A mensagem não foi informada';
    }
    try {
        if (!empty($erros)) {
            throw new \MZ\Exception\ValidationException($erros);
        }
        if (!mail_contato($email, $nome, $assunto, $mensagem)) {
            throw new \Exception("Não foi possível enviar o E-mail, por favor tente novamente mais tarde");
        }
        $app->getResponse('html')->output('contato_sucesso');
        exit;
    } catch (\ValidationException $e) {
        $erro = $e->getErrors();
    } catch (\Exception $e) {
        $erro['unknow'] = $e->getMessage();
    }
    foreach ($erro as $key => $value) {
        $focusctrl = $key;
        break;
    }
    \Thunder::error($erro[$focusctrl]);
}

$pagetitle = 'Contato';
$app->getResponse('html')->output('contato_index');

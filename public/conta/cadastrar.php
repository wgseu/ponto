<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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

if (is_login()) {
    \Thunder::information('Você já está cadastrado e autenticado!', true);
    redirect('/');
}
$fieldfocus = 'nome';
$gerenciando = false;
$cadastrar_cliente = true;
$erro = [];
if (is_post()) {
    $cliente = new Cliente($_POST);
    $cliente->setImagem(null);
    $cliente->setSlogan(null);
    $aceitar = $_POST['aceitar'];
    try {
        if ($aceitar != 'true') {
            throw new ValidationException(['aceitar' => 'Os termos não foram aceitos']);
        }
        if ($_POST['confirmarsenha'] != $_POST['senha']) {
            throw new ValidationException(['senha' => 'As senhas não são iguais', 'confirmarsenha' => 'As senhas não são iguais']);
        }
        if (trim($_POST['email']) == '') {
            throw new ValidationException(['email' => 'O E-mail não foi informado']);
        }
        if ($cliente->getTipo() == Cliente::TIPO_JURIDICA) {
            $cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CNPJ')));
        } else {
            $cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CPF')));
        }
        $cliente = Cliente::cadastrar($cliente, true);
        $login_cliente = $cliente;
        $login_cliente_id = $cliente->getID();
        Authentication::login($cliente->getID());
        redirect(get_redirect_page());
    } catch (ValidationException $e) {
        $erro = $e->getErrors();
    } catch (Exception $e) {
        $erro['unknow'] = $e->getMessage();
    }
} else {
    $cliente = new Cliente();
}
foreach ($erro as $key => $value) {
    $fieldfocus = $key;
    break;
}
if (array_key_exists($fieldfocus, $erro)) {
    \Thunder::error($erro[$fieldfocus]);
}
if ($fieldfocus == 'genero') {
    $fieldfocus .= '_m';
}

$pagetitle = 'Cadastrar';
include template('conta_cadastrar');

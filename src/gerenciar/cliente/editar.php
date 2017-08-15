<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager($_GET['saida'] == 'json');
$cliente = ZCliente::getPeloID($_GET['id']);
if (is_null($cliente->getID())) {
    $msg = 'O cliente de id "'.$id.'" não existe!';
    if ($_GET['saida'] == 'json') {
        json($msg);
    }
    Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
if ($cliente->getID() != $login_cliente->getID()) {
    need_permission(PermissaoNome::CADASTROCLIENTES, $_GET['saida'] == 'json');
}
if ($cliente->getID() == $__empresa__->getID() &&
    !have_permission(PermissaoNome::ALTERARCONFIGURACOES)) {
    $msg = 'Você não tem permissão para alterar essa empresa!';
    if ($_GET['saida'] == 'json') {
        json($msg);
    }
    Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
$funcionario = ZFuncionario::getPeloClienteID($cliente->getID());
if (!is_null($funcionario->getID()) && (
    (!have_permission(PermissaoNome::CADASTROFUNCIONARIOS) &&
     $login_funcionario->getID() != $funcionario->getID()) ||
    ( have_permission(PermissaoNome::CADASTROFUNCIONARIOS, $funcionario) &&
     $login_funcionario->getID() != $funcionario->getID() && !is_owner()) ) ) {
    $msg = 'Você não tem permissão para alterar as informações desse cliente!';
    if ($_GET['saida'] == 'json') {
        json($msg);
    }
    Thunder::warning($msg);
    redirect('/gerenciar/cliente/');
}
$focusctrl = 'nome';
$errors = array();
$old_cliente = $cliente;
if ($_POST) {
    $cliente = new ZCliente($_POST);
    try {
        $cliente->setID($old_cliente->getID());
        if ($cliente->getID() == $__empresa__->getID() && $cliente->getTipo() != ClienteTipo::JURIDICA) {
            throw new ValidationException(array('tipo' => 'O tipo da empresa deve ser jurídica'));
        }
        if (strlen($cliente->getSenha()) > 0 && $cliente->getSenha() != $_POST['confirmarsenha']) {
            throw new ValidationException(array('senha' => 'As senhas não são iguais'));
        }
        $cliente->setAcionistaID(numberval($cliente->getAcionistaID()));
        if ($cliente->getTipo() == ClienteTipo::JURIDICA) {
            $cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CNPJ')));
        } else {
            $cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CPF')));
        }
        $_data_aniversario = date_create_from_format('d/m/Y', $cliente->getDataAniversario());
        $cliente->setDataAniversario($_data_aniversario===false?null:date_format($_data_aniversario, 'Y-m-d'));
        $cliente->setFone(1, \MZ\Util\Filter::unmask($cliente->getFone(1), _p('Mascara', 'Telefone')));
        $cliente->setFone(2, \MZ\Util\Filter::unmask($cliente->getFone(2), _p('Mascara', 'Telefone')));
        $cliente->setLimiteCompra(moneyval($cliente->getLimiteCompra()));
        $width = 256;
        if ($cliente->getTipo() == ClienteTipo::JURIDICA) {
            $width = 640;
        }
        $imagem = upload_image('raw_imagem', 'cliente', null, $width, 256, true);
        if (!is_null($imagem)) {
            $cliente->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'cliente')));
            unlink(WWW_ROOT . get_image_url($imagem, 'cliente'));
        } elseif (trim($cliente->getImagem()) != '') { // evita sobrescrever
            $cliente->setImagem(true);
        }
        $cliente = ZCliente::atualizar($cliente);
        try {
            if ($cliente->getID() == $__empresa__->getID()) {
                $appsync = new AppSync();
                $appsync->enterpriseChanged();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        $msg = 'Cliente "'.$cliente->getNomeCompleto().'" atualizado com sucesso!';
        if ($_GET['saida'] == 'json') {
            json(array('status' => 'ok', 'item' => $cliente->toArray(array('secreto', 'senha')), 'msg' => $msg));
        }
        Thunder::success($msg, true);
        redirect('/gerenciar/cliente/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // restaura a foto original
    $cliente->setImagem($old_cliente->getImagem());
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        if ($_GET['saida'] == 'json') {
            json($value, null, array('field' => $focusctrl));
        }
        Thunder::error($value);
        break;
    }
}
if ($_GET['saida'] == 'json') {
    json('Nenhum dado foi enviado');
}
include template('gerenciar_cliente_editar');

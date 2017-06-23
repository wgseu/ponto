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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_login($_GET['saida'] == 'json');
$cliente = $login_cliente;
$tab_dados = 'selected';
$fieldfocus = 'nome';
$gerenciando = false;
$cadastrar_cliente = false;
$aceitar = 'true';
$erro = array();
if($_POST) {
	$old_cliente = $cliente;
	$cliente = new ZCliente($_POST);
	try {
		// não deixa o usuário alterar os dados abaixo
		$cliente->setID($old_cliente->getID());
		$cliente->setEmail($old_cliente->getEmail());
		$cliente->setSecreto($old_cliente->getSecreto());
		$cliente->setTipo($old_cliente->getTipo());
		$cliente->setAcionistaID($old_cliente->getAcionistaID());
		$cliente->setLimiteCompra($old_cliente->getLimiteCompra());

		if($cliente->getTipo() == ClienteTipo::JURIDICA)
			$cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CNPJ')));
		else
			$cliente->setCPF(\MZ\Util\Filter::unmask($cliente->getCPF(), _p('Mascara', 'CPF')));
		$_data_aniversario = date_create_from_format('d/m/Y', $cliente->getDataAniversario());
		$cliente->setDataAniversario($_data_aniversario===false?null:date_format($_data_aniversario, 'Y-m-d'));
		$cliente->setFone(1, \MZ\Util\Filter::unmask($cliente->getFone(1), _p('Mascara', 'Telefone')));
		$cliente->setFone(2, \MZ\Util\Filter::unmask($cliente->getFone(2), _p('Mascara', 'Telefone')));
		$width = 256;
		if($cliente->getTipo() == ClienteTipo::JURIDICA)
			$width = 640;
		$imagem = upload_image('raw_imagem', 'cliente', null, $width, 256, true);
		if(!is_null($imagem)) {
			$cliente->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'cliente')));
			unlink(WWW_ROOT . get_image_url($imagem, 'cliente'));
		} else if(trim($cliente->getImagem()) != '') // evita sobrescrever
			$cliente->setImagem(true);
		if ($_POST['confirmarsenha'] != $_POST['senha']) {
			throw new ValidationException(array('senha' => 'As senhas não são iguais', 'confirmarsenha' => 'As senhas não são iguais'));
		}
		$cliente = ZCliente::atualizar($cliente);
		$msg = 'Conta atualizada com sucesso!';
		if($_GET['saida'] == 'json')
			json(array('status' => 'ok', 'item' => $cliente->toArray(array('secreto', 'senha')), 'msg' => $msg));
		Thunder::success($msg, true);
		redirect('/conta/editar');
	} catch (ValidationException $e) {
		$erro = $e->getErrors();
	} catch (Exception $e) {
		$erro['unknow'] = $e->getMessage();
	}
	// restaura a foto original
	$cliente->setImagem($old_cliente->getImagem());
	foreach($erro as $key => $value) {
		$fieldfocus = $key;
		if($_GET['saida'] == 'json')
			json($value);
		Thunder::error($value);
		break;
	}
}
if($fieldfocus == 'sexo')
	$fieldfocus .= '_m';
if($_GET['saida'] == 'json')
	json('Nenhum dado foi enviado');
$pagetitle = 'Editar Conta';
include template('conta_editar');
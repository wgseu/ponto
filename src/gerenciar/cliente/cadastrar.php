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

need_permission(PermissaoNome::CADASTROCLIENTES, $_GET['saida'] == 'json');
$focusctrl = 'nome';
$errors = array();
if ($_POST) {
	$cliente = new ZCliente($_POST);
	try {
		DB::BeginTransaction();
		$cliente->setID(null);
		if(intval($_GET['sistema']) == 1 && $cliente->getTipo() != ClienteTipo::JURIDICA) {
			throw new ValidationException(array('tipo' => 'O tipo da empresa deve ser jurídica'));
		}
		if(intval($_GET['sistema']) == 1 && !is_null($__sistema__->getEmpresaID())) {
			throw new Exception('Você deve alterar a empresa "' . $__empresa__->getNomeCompleto() . '" em vez de cadastrar uma nova');
		}
		$cliente->setAcionistaID(numberval($cliente->getAcionistaID()));
		if($cliente->getTipo() == ClienteTipo::JURIDICA)
			$cliente->setCPF(unmask($cliente->getCPF(), '99.999.999/9999-99'));
		else
			$cliente->setCPF(unmask($cliente->getCPF(), '999.999.999-99'));
		$_data_aniversario = date_create_from_format('d/m/Y', $cliente->getDataAniversario());
		$cliente->setDataAniversario($_data_aniversario===false?null:date_format($_data_aniversario, 'Y-m-d'));
		$cliente->setFone(1, unmask($cliente->getFone(1), '(99) 9999-9999?9'));
		$cliente->setFone(2, unmask($cliente->getFone(2), '(99) 9999-9999?9'));
		$cliente->setLimiteCompra(moneyval($cliente->getLimiteCompra()));
		$width = 256;
		if($cliente->getTipo() == ClienteTipo::JURIDICA)
			$width = 640;
		$imagem = upload_image('raw_imagem', 'cliente', null, $width, 256, true);
		if(!is_null($imagem)) {
			$cliente->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'cliente')));
			unlink(WWW_ROOT . get_image_url($imagem, 'cliente'));
		} else
			$cliente->setImagem(null);
		$cliente = ZCliente::cadastrar($cliente);
		if(intval($_GET['sistema']) == 1) {
			$__sistema__->setEmpresaID($cliente->getID());
			$__sistema__ = ZSistema::atualizar($__sistema__, array('empresaid'));

			try {
				$appsync = new AppSync();
				$appsync->systemOptionsChanged();
				$appsync->enterpriseChanged();
			} catch (Exception $e) {
				Log::error($e->getMessage());
			}
		}
		DB::Commit();
		$msg = 'Cliente "'.$cliente->getNomeCompleto().'" cadastrado com sucesso!';
		if($_GET['saida'] == 'json')
			json(array('status' => 'ok', 'item' => $cliente->toArray(array('secreto', 'senha')), 'msg' => $msg));
		Thunder::success($msg, true);
		redirect('/gerenciar/cliente/');
	} catch (ValidationException $e) {
		$errors = $e->getErrors();
	} catch (Exception $e) {
		$errors['unknow'] = $e->getMessage();
	}
	DB::RollBack();
	// remove a foto enviada
	$cliente->setImagem(null);
	foreach($errors as $key => $value) {
		$focusctrl = $key;
		if($focusctrl == 'genero')
			$focusctrl = $focusctrl . '-' . strtolower(ClienteGenero::MASCULINO);
		if($_GET['saida'] == 'json')
			json($value, null, array('field' => $focusctrl));
		Thunder::error($value);
		break;
	}
} else {
	$cliente = new ZCliente();
}
if($_GET['saida'] == 'json')
	json('Nenhum dado foi enviado');
include template('gerenciar_cliente_cadastrar');
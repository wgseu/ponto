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
require_once(dirname(__DIR__) . '/app.php');

need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
$focusctrl = 'tipo';
$errors = [];
$old_cliente = $cliente;
if (is_post()) {
    $cliente = new Cliente($_POST);
    try {
        \DB::BeginTransaction();
        $cliente->setID(null);
        if (intval($_GET['sistema']) == 1 && $cliente->getTipo() != Cliente::TIPO_JURIDICA) {
            throw new ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
        }
        if (intval($_GET['sistema']) == 1 && !is_null($__sistema__->getEmpresaID())) {
            throw new \Exception('Você deve alterar a empresa "' . $__empresa__->getNomeCompleto() . '" em vez de cadastrar uma nova');
        }
        $cliente->setAcionistaID(numberval($cliente->getAcionistaID()));
        if ($cliente->getTipo() == Cliente::TIPO_JURIDICA) {
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
        if ($cliente->getTipo() == Cliente::TIPO_JURIDICA) {
            $width = 640;
        }
        $imagem = upload_image('raw_imagem', 'cliente', null, $width, 256, true);
        if (!is_null($imagem)) {
            $cliente->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'cliente')));
            unlink(WWW_ROOT . get_image_url($imagem, 'cliente'));
        } else {
            $cliente->setImagem(null);
        }
        $cliente->filter($old_cliente);
        $cliente->insert();
        $old_cliente->clean($cliente);
        if (intval($_GET['sistema']) == 1) {
            $__sistema__->setEmpresaID($cliente->getID());
            $__sistema__ = Sistema::atualizar($__sistema__, ['empresaid']);

            try {
                $appsync = new \MZ\System\Synchronizer();
                $appsync->systemOptionsChanged();
                $appsync->enterpriseChanged();
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        \DB::Commit();
        $msg = 'Cliente "'.$cliente->getNomeCompleto().'" cadastrado com sucesso!';
        if (is_output('json')) {
            json(['status' => 'ok', 'item' => $cliente->toArray(['secreto', 'senha']), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/cliente/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    \DB::RollBack();
    // remove a foto enviada
    $cliente->setImagem(null);
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        if ($focusctrl == 'genero') {
            $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
        }
        if (is_output('json')) {
            json($value, null, ['field' => $focusctrl]);
        }
        \Thunder::error($value);
        break;
    }
} else {
    $cliente = new Cliente();
}
if (is_output('json')) {
    json('Nenhum dado foi enviado');
}
include template('gerenciar_cliente_cadastrar');

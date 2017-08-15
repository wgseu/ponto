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

need_permission(PermissaoNome::CADASTROFORNECEDORES);
$produto_fornecedor = ZProdutoFornecedor::getPeloID($_GET['id']);
if (is_null($produto_fornecedor->getID())) {
    Thunder::warning('O produto de fornecedor de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/produto_fornecedor/');
}
$focusctrl = 'produtoid';
$errors = array();
$old_produto_fornecedor = $produto_fornecedor;
if ($_POST) {
    $produto_fornecedor = new ZProdutoFornecedor($_POST);
    try {
        $produto_fornecedor->setID($old_produto_fornecedor->getID());
        $produto_fornecedor->setPrecoCompra(moneyval($produto_fornecedor->getPrecoCompra()));
        $produto_fornecedor->setPrecoVenda(moneyval($produto_fornecedor->getPrecoVenda()));
        $produto_fornecedor->setQuantidadeMinima(moneyval($produto_fornecedor->getQuantidadeMinima()));
        $produto_fornecedor->setEstoque(moneyval($produto_fornecedor->getEstoque()));
        $_data_consulta = strtotime($produto_fornecedor->getDataConsulta());
        $produto_fornecedor->setDataConsulta(date('Y-m-d H:i:s', $_data_consulta));
        $produto_fornecedor = ZProdutoFornecedor::atualizar($produto_fornecedor);
        Thunder::success('Produto de fornecedor "'.$produto_fornecedor->getProdutoID().'" atualizado com sucesso!', true);
        redirect('/gerenciar/produto_fornecedor/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
}
$_produtos = ZProduto::getTodos();
$_fornecedores = ZFornecedor::getTodos();
include template('gerenciar_produto_fornecedor_editar');

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

need_permission(PermissaoNome::CADASTROPRODUTOS);
$produto = ZProduto::getPeloID($_GET['id']);
if (is_null($produto->getID())) {
    Thunder::warning('O produto de id "'.$_GET['id'].'" não existe!');
    redirect('/gerenciar/produto/');
}
$focusctrl = 'descricao';
$errors = array();
$old_produto = $produto;
if (is_post()) {
    $produto = new ZProduto($_POST);
    try {
        $produto->setID($old_produto->getID());
        $produto->setTributacaoID($old_produto->getTributacaoID());

        $produto->setQuantidadeLimite(moneyval($produto->getQuantidadeLimite()));
        $produto->setQuantidadeMaxima(moneyval($produto->getQuantidadeMaxima()));
        $produto->setConteudo(moneyval($produto->getConteudo()));
        $produto->setPrecoVenda(moneyval($produto->getPrecoVenda()));
        $produto->setCustoProducao(moneyval($produto->getCustoProducao()));
        $produto->setTempoPreparo(numberval($produto->getTempoPreparo()));
        $imagem = upload_image('raw_imagem', 'produto', null, 256, 256, true, 'crop');
        if (!is_null($imagem)) {
            $produto->setImagem(file_get_contents(WWW_ROOT . get_image_url($imagem, 'produto')));
            unlink(WWW_ROOT . get_image_url($imagem, 'produto'));
        } elseif (trim($produto->getImagem()) != '') { // evita sobrescrever
            $produto->setImagem(true);
        }
        $produto->setDataAtualizacao(date('Y-m-d H:i:s', time()));
        $produto = ZProduto::atualizar($produto);
        Thunder::success('Produto "'.$produto->getDescricao().'" atualizado com sucesso!', true);
        redirect('/gerenciar/produto/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // restaura a imagem original
    $produto->setImagem($old_produto->getImagem());
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
}
$_categorias = ZCategoria::getTodas(true);
$_unidades = ZUnidade::getTodas();
$_setores = ZSetor::getTodos();
include template('gerenciar_produto_editar');

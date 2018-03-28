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

use MZ\__TODO_NAMESPACE__\Produto;

need_permission(\Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$produto = Produto::findByID($id);
$produto->setID(null);

$focusctrl = 'codigobarras';
$errors = [];
$old_produto = $produto;
if (is_post()) {
    $produto = new Produto($_POST);
    try {
        $produto->setID(null);
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
        } else {
            $produto->setImagem(null);
        }
        $produto->setDataAtualizacao(date('Y-m-d H:i:s', time()));
        $produto->filter($old_produto);
        $produto->insert();
        $old_produto->clean($produto);
        $msg = sprintf(
            'Produto "%s" cadastrado com sucesso!',
            $produto->getDescricao()
        );
        if (is_output('json')) {
            json(null, ['item' => $produto->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/produto/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    // remove a imagem enviada
    $produto->setImagem(null);
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        \Thunder::error($value);
        break;
    }
} else {
    $unidade = Unidade::getPelaSigla('UN');
    $produto = new Produto();
    $produto->setTipo(Produto::TIPO_COMPOSICAO);
    $produto->setVisivel('Y');
    $produto->setCobrarServico('Y');
    $produto->setConteudo(1);
    $produto->setTempoPreparo(0);
    $produto->setUnidadeID($unidade->getID());
}
$_categorias = Categoria::getTodas(true);
$_unidades = Unidade::findAll();
$_setores = Setor::findAll();
include template('gerenciar_produto_cadastrar');

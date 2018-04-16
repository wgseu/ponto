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

use MZ\Product\Produto;
use MZ\Product\Categoria;
use MZ\Product\Unidade;
use MZ\Environment\Setor;
use MZ\System\Permissao;

need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
$id = isset($_GET['id']) ? $_GET['id'] : null;
$produto = Produto::findByID($id);
$produto->setID(null);
$produto->setImagem(null);

$focusctrl = 'codigobarras';
$errors = [];
$old_produto = $produto;
if (is_post()) {
    $produto = new Produto($_POST);
    try {
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
    } catch (\Exception $e) {
        $produto->clean($old_produto);
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
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
} elseif (!is_numeric($id)) {
    $unidade = Unidade::findBySigla(Unidade::SIGLA_UNITARIA);
    $produto->setTipo(Produto::TIPO_COMPOSICAO);
    $produto->setVisivel('Y');
    $produto->setCobrarServico('Y');
    $produto->setConteudo(1);
    $produto->setTempoPreparo(0);
    $produto->setUnidadeID($unidade->getID());
}
$_categorias = Categoria::findAll();
$_unidades = Unidade::findAll();
$_setores = Setor::findAll();
$app->getResponse('html')->output('gerenciar_produto_cadastrar');

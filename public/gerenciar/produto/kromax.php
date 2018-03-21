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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\System\Integracao;

define('INTGR_TOKEN', 'wKPZ1ABDOO9EVHJMuORwrFogsUPU7Ca5');

$integracao = Integracao::findByAcessoURL(\MZ\Integrator\Kromax::NAME);
$association = new \MZ\Association\Product($integracao);

if (isset($_GET['action'])) {
    if (is_post() && $_GET['action'] == 'update') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
            $association->update(
                $codigo,
                isset($_POST['id'])?$_POST['id']:null
            );
            $produtos = $association->getProdutos();
            json(null, ['produto' => $produtos[$codigo]]);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif (is_post() && $_GET['action'] == 'delete') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
            $subcodigo = isset($_POST['subcodigo'])?$_POST['subcodigo']:null;
            $association->delete($codigo, $subcodigo);
            if (isset($subcodigo)) {
                $msg = 'Item do pacote excluído com sucesso!';
            } else {
                $msg = 'Produto excluído com sucesso!';
            }
            json(null, ['msg' => $msg]);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif (is_post() && $_GET['action'] == 'mount') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
            $subcodigo = isset($_POST['subcodigo'])?$_POST['subcodigo']:null;
            $id = isset($_POST['id'])?$_POST['id']:null;
            $association->mount($codigo, $subcodigo, $id);
            $produtos = $association->getProdutos();
            json(null, ['pacote' => $produtos[$codigo]['itens'][$subcodigo]]);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif ($_GET['action'] == 'package') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            $codigo = isset($_GET['codigo'])?$_GET['codigo']:null;
            $package = $association->findPackage($codigo);
            json(null, $package);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    }
}
need_permission(PermissaoNome::CADASTROPRODUTOS);

$produtos = $association->findAll();

include template('gerenciar_produto_associar');

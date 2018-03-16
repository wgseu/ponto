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

$integracao = Integracao::findByAcessoURL(\MZ\Integrator\IFood::NAME);
$association = new \MZ\Association\Product($integracao);

if (isset($_GET['action'])) {
    if (is_post() && $_GET['action'] == 'upload') {
        if (!isset($_GET['token']) || $_GET['token'] != INTGR_TOKEN) {
            need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        }
        try {
            if (!isset($_FILES['raw_arquivo']) || $_FILES['raw_arquivo']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new \Exception('Nenhum arquivo foi enviado');
            }
            $file = $_FILES['raw_arquivo'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new \UploadException($file['error']);
            }
            if (in_array($file['type'], array('text/xml', 'application/xml'))) {
                $association->populate($file['tmp_name']);
            } elseif (in_array($file['type'], array('text/plain'))) {
                // migrate from INI file
                $produtos = $association->getProdutos();
                $content = file_get_contents($file['tmp_name']);
                $content = preg_replace('/\/[^=\/]*\/=[^\r\n]*[\r\n]*/', '', $content);
                $sections = parse_ini_string($content, true, INI_SCANNER_RAW);
                if (isset($sections['Codigos'])) {
                    foreach ($sections['Codigos'] as $codigo => $value) {
                        $produto = array(
                            'id' => $value,
                            'codigo' => $codigo,
                            'descricao' => 'Auto gerado pelo ifood.ini',
                            'itens' => array(),
                        );
                        if (isset($produtos[$codigo])) {
                            $produtos[$codigo] = array_merge(
                                $produto,
                                array_merge(
                                    $produtos[$codigo],
                                    array('id' => $value)
                                )
                            );
                        } else {
                            $found = false;
                            foreach ($produtos as $_codigo => $_produto) {
                                if (isset($_produto['itens'][$codigo])) {
                                    $found = true;
                                    $produtos[$_codigo]['itens'][$codigo] = array_merge(
                                        $_produto['itens'][$codigo],
                                        array_merge(
                                            $_produto['itens'][$codigo],
                                            array('id' => $value)
                                        )
                                    );
                                    break;
                                }
                            }
                            if (!$found) {
                                $produtos[$codigo] = $produto;
                            }
                        }
                    }
                    $dados = $association->getDados();
                    $dados = isset($dados)?$dados:array();
                    $dados['produtos'] = $produtos;
                    $integracao->write($dados);
                }
            } else {
                throw new \Exception('Formato não suportado', 401);
            }
            json(null, array('msg' => 'Upload realizado com sucesso'));
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif (is_post() && $_GET['action'] == 'update') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
            $association->update(
                $codigo,
                isset($_POST['id'])?$_POST['id']:null
            );
            $produtos = $association->getProdutos();
            json(null, array('produto' => $produtos[$codigo]));
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
            json(null, array('msg' => $msg));
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
            json(null, array('pacote' => $produtos[$codigo]['itens'][$subcodigo]));
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
    } elseif ($_GET['action'] == 'download') {
        if (!isset($_GET['token']) || $_GET['token'] != INTGR_TOKEN) {
            need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        }
        $card = new MZ\Association\Card($integracao);
        $cartoes = $card->getCartoes();
        $produtos = $association->getProdutos();
        $_cartoes = array();
        $_produtos = array();
        $_desconhecidos = array();
        foreach ($produtos as $codigo => $produto) {
            if (!is_null($produto['id'])) {
                $_produtos[$codigo] = $produto['id'];
            } elseif (!is_null($produto['codigo_pdv'])) {
                $_produtos[$codigo] = $produto['codigo_pdv'];
            } else {
                $_desconhecidos[$codigo] = $produto['descricao'];
            }
            foreach ($produto['itens'] as $subcodigo => $subproduto) {
                if (!is_null($subproduto['id'])) {
                    $_produtos[$subcodigo] = $subproduto['id'];
                } else {
                    $_desconhecidos[$subcodigo] = $subproduto['descricao'];
                }
            }
        }
        if (empty($cartoes)) {
            $cartoes['/^VVREST|RSODEX|TRE|VALECA|VR_SMA|AM|DNR|ELO|MC|VIS^/'] = 'iFood';
        }
        foreach ($cartoes as $regex => $cartao) {
            $_cartoes[$regex] = $cartao;
        }
        $ini = array(
            'Cartoes' => $_cartoes,
            'Codigos' => $_produtos,
            'Desconhecidos' => $_desconhecidos
        );
        $filename = 'ifood.ini';
        header('Content-Type: text/plain');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
        echo to_ini($ini);
        exit;
    }
}
need_permission(PermissaoNome::CADASTROPRODUTOS);

$produtos = $association->findAll();

include template('gerenciar_produto_associar');

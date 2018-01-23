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

define('IFOOD_TOKEN', 'wKPZ1ABDOO9EVHJMuORwrFogsUPU7Ca5');

$integracao = Integracao::findByAcessoURL('ifood');
$dados = $integracao->read();
$produtos = isset($dados['produtos'])?$dados['produtos']:array();
$cartoes = isset($dados['cartoes'])?$dados['cartoes']:array();

if (isset($_GET['action'])) {
    if (is_post() && $_GET['action'] == 'upload') {
        if (!isset($_GET['token']) || $_GET['token'] != IFOOD_TOKEN) {
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
                $dom = new \DOMDocument();
                if ($dom->load($file['tmp_name']) === false) {
                    throw new \Exception('Falha ao carregar XML', 401);
                }
                $nodes = $dom->getElementsByTagName('response-body');
                foreach ($nodes as $list) {
                    $itens = $list->getElementsByTagName('item');
                    foreach ($itens as $item) {
                        $codigo = $item->getElementsByTagName('codCardapio')->item(0)->nodeValue;
                        $temp = $item->getElementsByTagName('codProdutoPdv');
                        $codigo_pdv = $temp->length > 0?$temp->item(0)->nodeValue:null;
                        $temp = $item->getElementsByTagName('codPai');
                        $codigo_pai = $temp->length > 0?$temp->item(0)->nodeValue:null;
                        $descricao = $item->getElementsByTagName('descricaoCardapio')->item(0)->nodeValue;
                        $produto = array(
                            'codigo' => $codigo,
                            'codigo_pai' => $codigo_pai,
                            'codigo_pdv' => $codigo_pdv,
                            'descricao' => $descricao,
                            'itens' => array(),
                        );
                        if (isset($produtos[$codigo_pai])) {
                            unset($produto['itens']);
                            if (isset($produtos[$codigo_pai]['itens'][$codigo])) {
                                $produtos[$codigo_pai]['itens'][$codigo] = array_merge(
                                    $produto,
                                    $produtos[$codigo_pai]['itens'][$codigo]
                                );
                            } else {
                                $produtos[$codigo_pai]['itens'][$codigo] = $produto;
                            }
                        } else {
                            unset($produto['codigo_pai']);
                            if (isset($produtos[$codigo])) {
                                $produtos[$codigo] = array_merge(
                                    $produto,
                                    $produtos[$codigo]
                                );
                            } else {
                                $produtos[$codigo] = $produto;
                            }
                        }
                    }
                }
                $dados = isset($dados)?$dados:array();
                $dados['produtos'] = $produtos;
                $integracao->write($dados);
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
            if (!isset($_POST['codigo']) || !isset($_POST['id'])) {
                throw new \Exception('Código ou ID inválido', 401);
            }
            $codigo = $_POST['codigo'];
            if (!isset($produtos[$codigo])) {
                throw new \Exception('O produto informado não existe', 404);
            }
            $produtos[$codigo]['id'] = $_POST['id'];
            $dados = isset($dados)?$dados:array();
            $dados['produtos'] = $produtos;
            $integracao->write($dados);
            $appsync = new AppSync();
            $appsync->integratorChanged();
            json(null, array('produto' => $produtos[$codigo]));
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif (is_post() && $_GET['action'] == 'delete') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            if (!isset($_POST['codigo'])) {
                throw new \Exception('Código inválido ou não informado', 401);
            }
            $codigo = $_POST['codigo'];
            if (!isset($produtos[$codigo])) {
                throw new \Exception('O produto informado não existe', 404);
            }
            $subcodigo = isset($_POST['subcodigo'])?$_POST['subcodigo']:null;
            if (isset($subcodigo) && !isset($produtos[$codigo]['itens'][$subcodigo])) {
                throw new \Exception('O item informado não existe no pacote', 404);
            }
            if (isset($subcodigo)) {
                unset($produtos[$codigo]['itens'][$subcodigo]);
            } else {
                unset($produtos[$codigo]);
            }
            $dados = isset($dados)?$dados:array();
            $dados['produtos'] = $produtos;
            $integracao->write($dados);
            if (isset($subcodigo)) {
                $msg = 'Item do pacote excluído com sucesso!';
            } else {
                $msg = 'Produto excluído com sucesso!';
            }
            $appsync = new AppSync();
            $appsync->integratorChanged();
            json(null, array('msg' => $msg));
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif (is_post() && $_GET['action'] == 'mount') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            if (!isset($_POST['codigo']) || !isset($_POST['id'])) {
                throw new \Exception('Código ou ID inválido', 401);
            }
            $codigo = $_POST['codigo'];
            if (!isset($produtos[$codigo])) {
                throw new \Exception('O produto informado não existe', 404);
            }
            $subcodigo = $_POST['subcodigo'];
            if (!isset($produtos[$codigo]['itens'][$subcodigo])) {
                throw new \Exception('O item do pacote não existe', 404);
            }
            $produtos[$codigo]['itens'][$subcodigo]['id'] = $_POST['id'];
            $dados = isset($dados)?$dados:array();
            $dados['produtos'] = $produtos;
            $integracao->write($dados);
            $appsync = new AppSync();
            $appsync->integratorChanged();
            json(null, array('pacote' => $produtos[$codigo]['itens'][$subcodigo]));
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif ($_GET['action'] == 'package') {
        need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        try {
            if (!isset($_GET['codigo'])) {
                throw new \Exception('Código não informado', 401);
            }
            $codigo = $_GET['codigo'];
            if (!isset($produtos[$codigo])) {
                throw new \Exception('O produto informado não existe', 404);
            }
            $produto = $produtos[$codigo];
            $associado = \ZProduto::getPeloID(isset($produto['id'])?$produto['id']:$produto['codigo_pdv']);
            if (is_null($associado->getID())) {
                throw new \Exception('O produto informado não foi associado', 401);
            }
            if ($associado->getTipo() == \ProdutoTipo::PRODUTO) {
                throw new \Exception('O produto associado não permite formação', 401);
            }
            $produto['tipo'] = $associado->getTipo();
            $_grupos = \ZGrupo::getTodosDoProdutoID($associado->getID());
            $grupos = array();
            $contagem = array();
            $total_pacotes = 0;
            foreach ($_grupos as $grupo) {
                $grupos[] = $grupo->toArray();
                $qtd_pacotes = \ZPacote::getCountDoGrupoID($grupo->getID());
                $contagem[] = $qtd_pacotes;
                $total_pacotes += $qtd_pacotes;
            }
            $grupo = new \ZGrupo();
            $grupo->setID(0);
            $grupo->setDescricao('Adicionais');
            if ($associado->getTipo() == \ProdutoTipo::PACOTE) {
                $grupo->setDescricao('Sem grupo');
            }
            $total_igual = count($produto['itens']) == $total_pacotes;
            if ((count($grupos) > 1 && !$total_igual) || count($grupos) == 0) {
                $grupos[] = $grupo->toArray();
                $contagem[] = count($produto['itens']);
            }
            $total_pacotes = 0;
            $grupo_index = 0;
            foreach ($produto['itens'] as $subcodigo => $subproduto) {
                if ($associado->getTipo() == \ProdutoTipo::PACOTE) {
                    $subassociado = \ZPacote::getPeloID(
                        isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']
                    );
                    if ($subassociado->getPacoteID() != $associado->getID()) {
                        $subassociado = new \ZPacote();
                    }
                    $grupoid = intval($subassociado->getGrupoID());
                    if (($total_igual || count($grupos) == 1) && $grupo_index < count($grupos) && $grupoid == 0) {
                        $grupoid = $grupos[$grupo_index]['id'];
                    }
                    $produto['itens'][$subcodigo]['grupoid'] = $grupoid;
                    $total_pacotes++;
                    if ($grupo_index < count($contagem) && $total_pacotes == $contagem[$grupo_index] && $grupo_index < count($grupos) - 1) {
                        $grupo_index++;
                        $total_pacotes = 0;
                    }
                    // if (count($grupos) == 1) {
                    //     $produto['itens'][$subcodigo]['grupoid'] = current($grupos)['id'];
                    // } else {
                    // }
                    if (!is_null($subassociado->getPropriedadeID())) {
                        $item = \ZPropriedade::getPeloID($subassociado->getPropriedadeID());
                    } else {
                        $item = \ZProduto::getPeloID($subassociado->getProdutoID());
                    }
                } else {
                    $subassociado = \ZComposicao::getPeloID(
                        isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']
                    );
                    if ($subassociado->getComposicaoID() != $associado->getID()) {
                        $subassociado = new \ZComposicao();
                    }
                    $produto['itens'][$subcodigo]['grupoid'] = 0;
                    $item = \ZProduto::getPeloID($subassociado->getProdutoID());
                }
                $produto['itens'][$subcodigo]['associado'] = $item->toArray();
            }
            json(null, array('produto' => $produto, 'grupos' => $grupos));
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    } elseif ($_GET['action'] == 'download') {
        if (!isset($_GET['token']) || $_GET['token'] != IFOOD_TOKEN) {
            need_permission(PermissaoNome::CADASTROPRODUTOS, true);
        }
        $_cartoes = array();
        $_produtos = array();
        $_desconhecidos = array();
        foreach ($produtos as $codigo => $produto) {
            if (!is_null($produto['id'])) {
                $_produtos[$codigo] = $produto['id'];
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
            $cartoes['/[\w]+ \*\*\*\* [0-9]{4}/'] = 'iFood';
            $cartoes['/^MASTERCARD|VISA$/'] = 'iFood';
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
foreach ($produtos as $codigo => $produto) {
    $associado = \ZProduto::getPeloID(isset($produto['id'])?$produto['id']:$produto['codigo_pdv']);
    $produtos[$codigo]['produto'] = $associado;
    $associados = 0;
    foreach ($produto['itens'] as $subcodigo => $subproduto) {
        if ($associado->getTipo() == \ProdutoTipo::PACOTE) {
            $subassociado = \ZPacote::getPeloID(isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']);
            if ($subassociado->getPacoteID() != $associado->getID()) {
                $subassociado = new \ZPacote();
            }
            if (!is_null($subassociado->getPropriedadeID())) {
                $item = \ZPropriedade::getPeloID($subassociado->getPropriedadeID());
            } else {
                $item = \ZProduto::getPeloID($subassociado->getProdutoID());
            }
        } else {
            $subassociado = \ZComposicao::getPeloID(
                isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']
            );
            if ($subassociado->getComposicaoID() != $associado->getID()) {
                $subassociado = new \ZComposicao();
            }
            $item = \ZProduto::getPeloID($subassociado->getProdutoID());
        }
        if (!is_null($item->getID())) {
            $associados++;
        }
        $produtos[$codigo]['itens'][$subcodigo]['associado'] = $item->toArray();
    }
    $status = '';
    if (is_null($associado->getID())) {
        $status = 'empty';
    } elseif ($associado->getTipo() == \ProdutoTipo::PRODUTO && count($produto['itens']) > 0) {
        $status = 'error';
    } elseif (count($produto['itens']) != $associados) {
        $status = 'incomplete';
    }
    $produtos[$codigo]['status'] = $status;
    $produtos[$codigo]['icon'] = count($produto['itens']) > 0 && !is_null($associado->getID())?'edit':'save';
}
include template('gerenciar_produto_ifood');

<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\Product;

use MZ\System\Integracao;
use MZ\System\Permissao;
use MZ\Stock\Estoque;
use MZ\Environment\Setor;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class ProdutoPageController extends PageController
{
    public function ifood()
    {
        define('INTGR_TOKEN', 'wKPZ1ABDOO9EVHJMuORwrFogsUPU7Ca5');

        $integracao = Integracao::findByAcessoURL(\MZ\Integrator\IFood::NAME);
        $association = new \MZ\Association\Product($integracao);

        if ($this->getRequest()->query->has('action')) {
            if ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'upload') {
                if ($this->getRequest()->query->get('token') != INTGR_TOKEN) {
                    $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                }
                try {
                    if (!isset($_FILES['raw_arquivo']) || $_FILES['raw_arquivo']['error'] === UPLOAD_ERR_NO_FILE) {
                        throw new \Exception('Nenhum arquivo foi enviado');
                    }
                    $file = $_FILES['raw_arquivo'];
                    if ($file['error'] !== UPLOAD_ERR_OK) {
                        throw new \MZ\Exception\UploadException($file['error']);
                    }
                    if (in_array($file['type'], ['text/xml', 'application/xml'])) {
                        $association->populate($file['tmp_name']);
                    } elseif (in_array($file['type'], ['text/plain'])) {
                        // migrate from INI file
                        $produtos = $association->getProdutos();
                        $content = file_get_contents($file['tmp_name']);
                        $content = preg_replace('/\/[^=\/]*\/=[^\r\n]*[\r\n]*/', '', $content);
                        $sections = parse_ini_string($content, true, INI_SCANNER_RAW);
                        if (isset($sections['Codigos'])) {
                            foreach ($sections['Codigos'] as $codigo => $value) {
                                $produto = [
                                    'id' => $value,
                                    'codigo' => $codigo,
                                    'descricao' => 'Auto gerado pelo ifood.ini',
                                    'itens' => [],
                                ];
                                if (isset($produtos[$codigo])) {
                                    $produtos[$codigo] = array_merge(
                                        $produto,
                                        array_merge(
                                            $produtos[$codigo],
                                            ['id' => $value]
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
                                                    ['id' => $value]
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
                            $dados = isset($dados)?$dados:[];
                            $dados['produtos'] = $produtos;
                            $integracao->write($dados);
                        }
                    } else {
                        throw new \Exception('Formato não suportado', 401);
                    }
                    return $this->json()->success([], 'Upload realizado com sucesso');
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'update') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $association->update(
                        $codigo,
                        $this->getRequest()->request->get('id')
                    );
                    $produtos = $association->getProdutos();
                    return $this->json()->success(['produto' => $produtos[$codigo]]);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'delete') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $subcodigo = $this->getRequest()->request->get('subcodigo');
                    $association->delete($codigo, $subcodigo);
                    if (isset($subcodigo)) {
                        $msg = 'Item do pacote excluído com sucesso!';
                    } else {
                        $msg = 'Produto excluído com sucesso!';
                    }
                    return $this->json()->success([], $msg);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'mount') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $subcodigo = $this->getRequest()->request->get('subcodigo');
                    $id = $this->getRequest()->request->get('id');
                    $association->mount($codigo, $subcodigo, $id);
                    $produtos = $association->getProdutos();
                    return $this->json()->success(['pacote' => $produtos[$codigo]['itens'][$subcodigo]]);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->query->get('action') == 'package') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->query->get('codigo');
                    $package = $association->findPackage($codigo);
                    return $this->json()->success($package);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->query->get('action') == 'download') {
                if ($this->getRequest()->query->get('token') != INTGR_TOKEN) {
                    $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                }
                $codigos = \MZ\Integrator\IFood::CARDS;
                $card = new \MZ\Association\Card($integracao, $codigos);
                $cartoes = $card->getCartoes();
                $produtos = $association->getProdutos();
                $_cartoes = [];
                $_produtos = [];
                $_desconhecidos = [];
                foreach ($produtos as $codigo => $produto) {
                    if (isset($produto['id'])) {
                        $_produtos[$codigo] = $produto['id'];
                    } elseif (isset($produto['codigo_pdv'])) {
                        $_produtos[$codigo] = $produto['codigo_pdv'];
                    } else {
                        $_desconhecidos[$codigo] = $produto['descricao'];
                    }
                    foreach ($produto['itens'] as $subcodigo => $subproduto) {
                        if (isset($subproduto['id'])) {
                            $_produtos[$codigo.'_'.$subcodigo] = $subproduto['id'];
                        } else {
                            $_desconhecidos[$codigo.'_'.$subcodigo] = $subproduto['descricao'];
                        }
                    }
                }
                if (empty($cartoes)) {
                    $cartoes['/^VVREST|RSODEX|TRE|VALECA|VR_SMA|AM|DNR|ELO|MC|VIS^/'] = 'iFood';
                }
                foreach ($cartoes as $regex => $cartao) {
                    $_cartoes[$regex] = $cartao;
                }
                $ini = [
                    'Cartoes' => $_cartoes,
                    'Codigos' => $_produtos,
                    'Desconhecidos' => $_desconhecidos
                ];
                $filename = 'ifood.ini';
                header('Content-Type: text/plain');
                header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
                echo to_ini($ini);
                exit;
            }
        }
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);

        $produtos = $association->findAll();

        return $this->view('gerenciar_produto_associar', get_defined_vars());
    }

    public function kromax()
    {
        $integracao = Integracao::findByAcessoURL(\MZ\Integrator\Kromax::NAME);
        $association = new \MZ\Association\Product($integracao);

        if ($this->getRequest()->query->has('action')) {
            if ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'update') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $association->update(
                        $codigo,
                        $this->getRequest()->request->get('id')
                    );
                    $produtos = $association->getProdutos();
                    return $this->json()->success(['produto' => $produtos[$codigo]]);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'delete') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $subcodigo = $this->getRequest()->request->get('subcodigo');
                    $association->delete($codigo, $subcodigo);
                    if (isset($subcodigo)) {
                        $msg = 'Item do pacote excluído com sucesso!';
                    } else {
                        $msg = 'Produto excluído com sucesso!';
                    }
                    return $this->json()->success([], $msg);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->isMethod('POST') && $this->getRequest()->query->get('action') == 'mount') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->request->get('codigo');
                    $subcodigo = $this->getRequest()->request->get('subcodigo');
                    $id = $this->getRequest()->request->get('id');
                    $association->mount($codigo, $subcodigo, $id);
                    $produtos = $association->getProdutos();
                    return $this->json()->success(['pacote' => $produtos[$codigo]['itens'][$subcodigo]]);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            } elseif ($this->getRequest()->query->get('action') == 'package') {
                $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
                try {
                    $codigo = $this->getRequest()->query->get('codigo');
                    $package = $association->findPackage($codigo);
                    return $this->json()->success($package);
                } catch (\Exception $e) {
                    return $this->json()->error($e->getMessage());
                }
            }
        }
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);

        $produtos = $association->findAll();

        return $this->view('gerenciar_produto_associar', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'produto_ifood',
                'path' => '/gerenciar/produto/ifood',
                'method' => ['GET', 'POST'],
                'controller' => 'ifood',
            ],
            [
                'name' => 'produto_kromax',
                'path' => '/gerenciar/produto/kromax',
                'method' => ['GET', 'POST'],
                'controller' => 'kromax',
            ],
        ];
    }
}

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
    public function display()
    {
        $pagetitle = 'Produtos';
        $categorias = Categoria::findAll(['disponivel' => 'Y'], ['vendas' => -1]);
        if (count($categorias) > 0) {
            $categoria_atual = current($categorias);
            $negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
            $condition = [
                'categoriaid' => $categoria_atual->getID(),
                'visivel' => 'Y',
                'permitido' => 'Y',
                'promocao' => 'Y'
            ];
            if (!$negativo) {
                $condition['disponivel'] = 'Y';
            }
            $produtos = Produto::findAll($condition);
        } else {
            $produtos = [];
            $categoria_atual = new Categoria();
        }
        return $this->view('produto_index', get_defined_vars());
    }

    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $condition['promocao'] = 'N';
        $produto = new Produto($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Produto::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $produtos = Produto::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($produtos as $_produto) {
                $items[] = $_produto->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $tipos = Produto::getTipoOptions();
        $categorias = [];
        $_categorias = Categoria::findAll();
        foreach ($_categorias as $categoria) {
            $categorias[$categoria->getID()] = $categoria->getDescricao();
        }
        $unidades = [];
        $_unidades = Unidade::findAll();
        foreach ($_unidades as $unidade) {
            $unidades[$unidade->getID()] = $unidade->getNome();
        }
        return $this->view('gerenciar_produto_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $produto = Produto::find(['id' => $id, 'promocao' => 'N']);
        $produto->setID(null);
        $produto->setImagem(null);

        $focusctrl = 'codigobarras';
        $errors = [];
        $old_produto = $produto;
        if ($this->getRequest()->isMethod('POST')) {
            $produto = new Produto($this->getData());
            try {
                $produto->filter($old_produto, true);
                $produto->insert();
                $old_produto->clean($produto);
                $produto->load(['id' => $produto->getID(), 'promocao' => 'N']);
                $msg = sprintf(
                    'Produto "%s" cadastrado com sucesso!',
                    $produto->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $produto->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/produto/');
            } catch (\Exception $e) {
                $produto->clean($old_produto);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($this->isJson()) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        } elseif (!is_numeric($id)) {
            $unidade = Unidade::findBySigla(Unidade::SIGLA_UNITARIA);
            $produto->setTipo(Produto::TIPO_COMPOSICAO);
            $produto->setVisivel('Y');
            $produto->setDivisivel('Y');
            $produto->setCobrarServico('Y');
            $produto->setConteudo(1);
            $produto->setTempoPreparo(0);
            $produto->setUnidadeID($unidade->getID());
        }
        $_categorias = Categoria::findAll();
        $_unidades = Unidade::findAll();
        $_setores = Setor::findAll();
        return $this->view('gerenciar_produto_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $produto = Produto::find(['id' => $id, 'promocao' => 'N']);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/produto/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_produto = $produto;
        if ($this->getRequest()->isMethod('POST')) {
            $produto = new Produto($this->getData());
            try {
                $produto->filter($old_produto, true);
                $produto->update();
                $old_produto->clean($produto);
                $produto->load(['id' => $produto->getID(), 'promocao' => 'N']);
                $msg = sprintf(
                    'Produto "%s" atualizado com sucesso!',
                    $produto->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $produto->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/produto/');
            } catch (\Exception $e) {
                $produto->clean($old_produto);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($this->isJson()) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $_categorias = Categoria::findAll();
        $_unidades = Unidade::findAll();
        $_setores = Setor::findAll();
        return $this->view('gerenciar_produto_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $produto = Produto::findByID($id);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/produto/');
        }
        try {
            $produto->delete();
            $produto->clean(new Produto());
            $msg = sprintf('Produto "%s" excluído com sucesso!', $produto->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o produto "%s"',
                $produto->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/produto/');
    }

    public function costs()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $produto = Produto::findByID($id);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/produto/');
        }
        if ($produto->getTipo() != Produto::TIPO_COMPOSICAO) {
            $msg = sprintf('O produto "%s" não é uma composição', $produto->getDescricao());
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/produto/');
        }
        $nos = [];
        $stack = new \SplStack();
        $composicao = new Composicao();
        $composicao->setID(0); // código do pai
        $composicao->setComposicaoID(null); // não possui pai
        $composicao->setProdutoID($produto->getID());
        $composicao->setQuantidade($produto->getConteudo());
        $stack->push($composicao);
        while (!$stack->isEmpty()) {
            $composicao = $stack->pop();
            $_produto = $composicao->findProdutoID();
            if ($_produto->getTipo() == Produto::TIPO_PACOTE) {
                continue;
            }
            if ($_produto->getTipo() == Produto::TIPO_COMPOSICAO) {
                $valor = 0.0;
                $composicoes = Composicao::findAll([
                    'composicaoid' => $composicao->getProdutoID(),
                    'tipo' => [Composicao::TIPO_COMPOSICAO, Composicao::TIPO_OPCIONAL],
                    'ativa' => 'Y'
                ]);
                foreach ($composicoes as $_composicao) {
                    $_composicao->setQuantidade($_composicao->getQuantidade() * $composicao->getQuantidade());
                    $_composicao->setComposicaoID($composicao->getID()); // salva o código do pai
                    $stack->push($_composicao);
                }
            } else { // o composto é um produto
                $valor = Estoque::getUltimoPrecoCompra($_produto->getID());
            }
            $composicao->setValor($valor);
            $no = [];
            $no['produto'] = $_produto;
            $no['composicao'] = $composicao;
            $nos[$composicao->getID()] = $no;
        }
        foreach (array_reverse($nos) as $no) {
            $_composicao = $no['composicao'];
            if (is_null($_composicao->getComposicaoID())) {
                continue;
            }
            $composicao = $nos[$_composicao->getComposicaoID()]['composicao'];
            $total = $composicao->getValor() * $composicao->getQuantidade() +
                $_composicao->getValor() * $_composicao->getQuantidade();
            $composicao->setValor($total / $composicao->getQuantidade());
        }
        return $this->view('gerenciar_produto_diagrama', get_defined_vars());
    }

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
                'name' => 'produto_display',
                'path' => '/produto/',
                'method' => 'GET',
                'controller' => 'display',
            ],
            [
                'name' => 'produto_find',
                'path' => '/gerenciar/produto/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'produto_add',
                'path' => '/gerenciar/produto/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'produto_update',
                'path' => '/gerenciar/produto/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'produto_delete',
                'path' => '/gerenciar/produto/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
            [
                'name' => 'produto_costs',
                'path' => '/gerenciar/produto/diagrama',
                'method' => 'GET',
                'controller' => 'costs',
            ],
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

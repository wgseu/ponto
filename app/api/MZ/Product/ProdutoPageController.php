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

/**
 * Allow application to serve system resources
 */
class ProdutoPageController extends \MZ\Core\Controller
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
        $response = $this->getResponse();
        $response->getEngine()->categorias = $categorias;
        $response->getEngine()->produtos = $produtos;
        $response->getEngine()->categoria_atual = $categoria_atual;
        return $response->output('produto_index');
    }

    public function find()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $condition['promocao'] = 'N';
        $produto = new Produto($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Produto::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $produtos = Produto::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($produtos as $_produto) {
                $items[] = $_produto->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
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
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $produto = Produto::find(['id' => $id, 'promocao' => 'N']);
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
                $produto->load(['id' => $produto->getID(), 'promocao' => 'N']);
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
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $produto = Produto::find(['id' => $id, 'promocao' => 'N']);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/produto/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_produto = $produto;
        if (is_post()) {
            $produto = new Produto($_POST);
            try {
                $produto->filter($old_produto);
                $produto->update();
                $old_produto->clean($produto);
                $produto->load(['id' => $produto->getID(), 'promocao' => 'N']);
                $msg = sprintf(
                    'Produto "%s" atualizado com sucesso!',
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
        }
        $_categorias = Categoria::findAll();
        $_unidades = Unidade::findAll();
        $_setores = Setor::findAll();
        return $this->view('gerenciar_produto_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $produto = Produto::findByID($id);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/produto/');
        }
        try {
            $produto->delete();
            $produto->clean(new Produto());
            $msg = sprintf('Produto "%s" excluído com sucesso!', $produto->getDescricao());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o produto "%s"',
                $produto->getDescricao()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/produto/');
    }

    public function costs()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $produto = Produto::findByID($id);
        if (!$produto->exists()) {
            $msg = 'O produto não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/produto/');
        }
        if ($produto->getTipo() != Produto::TIPO_COMPOSICAO) {
            $msg = sprintf('O produto "%s" não é uma composição', $produto->getDescricao());
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/produto/');
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

        if (isset($_GET['action'])) {
            if (is_post() && $_GET['action'] == 'upload') {
                if (!isset($_GET['token']) || $_GET['token'] != INTGR_TOKEN) {
                    need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                    json(null, ['msg' => 'Upload realizado com sucesso']);
                } catch (\Exception $e) {
                    json($e->getMessage());
                }
            } elseif (is_post() && $_GET['action'] == 'update') {
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
                try {
                    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
                    $package = $association->findPackage($codigo);
                    json(null, $package);
                } catch (\Exception $e) {
                    json($e->getMessage());
                }
            } elseif ($_GET['action'] == 'download') {
                if (!isset($_GET['token']) || $_GET['token'] != INTGR_TOKEN) {
                    need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));

        $produtos = $association->findAll();

        return $this->view('gerenciar_produto_associar', get_defined_vars());
    }

    public function kromax()
    {
        $integracao = Integracao::findByAcessoURL(\MZ\Integrator\Kromax::NAME);
        $association = new \MZ\Association\Product($integracao);

        if (isset($_GET['action'])) {
            if (is_post() && $_GET['action'] == 'update') {
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
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
                need_permission(Permissao::NOME_CADASTROPRODUTOS, true);
                try {
                    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
                    $package = $association->findPackage($codigo);
                    json(null, $package);
                } catch (\Exception $e) {
                    json($e->getMessage());
                }
            }
        }
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));

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

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
namespace MZ\Sale;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Product\Grupo;
use MZ\Product\Composicao;

/**
 * Montagem de pacote, informa a formação de um pacote
 */
class Montagem extends ProdutoPedido
{
    /**
     * Grupos formados
     */
    private $grupos;

    /**
     * Itens do pedido
     */
    private $itens;

    /**
     * Constructor for a new empty instance of Montagem
     * @param array $montagem All field and values to fill the instance
     */
    public function __construct($montagem = [])
    {
        parent::__construct($montagem);
    }

    /**
     * Lista de grupos formados
     * @return array lista de grupos
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Informa a nova lista de grupos formados
     * @param  array $grupos nova list
     * @return Montagem Própria instância
     */
    public function setGrupos($grupos)
    {
        $this->grupos = [];
        foreach ($grupos as $grupo) {
            $this->addGroup($grupo);
        }
        return $this;
    }

    /**
     * Lista de itens após montado
     * @return array lista de itens
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * Informa a lista de itens manualmente, cada item deve ser um array com chaves item e formacoes
     * @param  array $itens nova list
     * @return Montagem Própria instância
     */
    public function setItens($itens)
    {
        $this->itens = [];
        foreach ($itens as $info) {
            $this->addItem($info['item'], $info['formacoes']);
        }
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $montagem = parent::toArray($recursive);
        $montagem['grupos'] = $this->getGrupos();
        $montagem['itens'] = $this->getItens();
        return $montagem;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $montagem Associated key -> value to assign into this instance
     * @return Montagem Self instance
     */
    public function fromArray($montagem = [])
    {
        if ($montagem instanceof ProdutoPedido) {
            $montagem = $montagem->toArray();
        } elseif (!is_array($montagem)) {
            $montagem = [];
        }
        parent::fromArray($montagem);
        if (!isset($montagem['grupos'])) {
            $this->setGrupos([]);
        } else {
            $this->setGrupos($montagem['grupos']);
        }
        if (!isset($montagem['itens'])) {
            $this->setItens([]);
        } else {
            $this->setItens($montagem['itens']);
        }
        return $this;
    }

    /**
     * Adiciona um grupo na montagem do pacote
     * @param \MZ\Product\Grupo $grupo grupo que será adicionado
     * @return Montagem Self instance
     */
    public function addGroup($grupo)
    {
        $this->grupos[$grupo->getID()] = [
            'grupo' => $grupo,
            'somatorio' => 0,
            'pacotes' => []
        ];
        return $this;
    }

    /**
     * Adiciona um pacote em um grupo e pré-calcula mínimos e máximos
     * @param \MZ\Product\Pacote $pacote pacote que será adicionado
     * @param array $composicoes lista de composições adicionadas e retiradas do pacote
     * @param int $item_index índice do item ao qual esse pacote foi associado
     */
    private function addPacote($pacote, $composicoes, $item_index)
    {
        $item = $this->itens[$item_index]['item'];
        // TODO: verificar se o pacote está ativo
        $produto = $pacote->findProdutoID();
        // verifica se a propriedade ou o produto está em algum grupo do pacote
        if (!isset($this->grupos[$pacote->getGrupoID()])) {
            if (is_null($pacote->getPropriedadeID())) {
                $msg = 'O produto "%s" não faz parte do pacote "%s"';
                $descricao = $produto->getDescricao();
            } else {
                $msg = 'A propriedade "%s" não faz parte do pacote "%s"';
                $propriedade = $pacote->findPropriedadeID();
                $descricao = $propriedade->getNome();
            }
            $produto_pacote = $this->findProdutoID();
            throw new \Exception(
                sprintf(
                    $msg,
                    $descricao,
                    $produto_pacote->getDescricao()
                )
            );
        }
        $grupo = $this->grupos[$pacote->getGrupoID()]['grupo'];
        if ($grupo->getTipo() == Grupo::TIPO_INTEIRO) {
            // retorna a quantidade para unitária
            $minimo = $this->getQuantidade();
        } else {
            if (isset($this->grupos[$pacote->getGrupoID()]['minimo'])) {
                $minimo = $this->grupos[$pacote->getGrupoID()]['minimo'];
                $minimo = min($item->getQuantidade(), $minimo);
            } else {
                $minimo = $item->getQuantidade();
            }
        }
        $preco = $produto->getPrecoVenda() + $pacote->getValor();
        $preco += $composicoes['preco'];
        if (isset($this->grupos[$pacote->getGrupoID()]['menor'])) {
            $menor = $this->grupos[$pacote->getGrupoID()]['menor'];
            $menor = min($preco, $menor);
        } else {
            $menor = $preco;
        }
        if (isset($this->grupos[$pacote->getGrupoID()]['maior'])) {
            $maior = $this->grupos[$pacote->getGrupoID()]['maior'];
            $maior = max($preco, $maior);
        } else {
            $maior = $preco;
        }
        $this->grupos[$pacote->getGrupoID()]['minimo'] = $minimo;
        $this->grupos[$pacote->getGrupoID()]['menor'] = $menor;
        $this->grupos[$pacote->getGrupoID()]['maior'] = $maior;
        $this->grupos[$pacote->getGrupoID()]['pacotes'][] = [
            'pacote' => $pacote,
            'preco' => $preco,
            'composicoes' => $composicoes['itens'],
            'item_index' => $item_index
        ];
    }

    /**
     * Adiciona um item e suas formações à montagem do pacote
     * @param ProdutoPedido $item item do pacote
     * @param array $formacoes lista de formações do item
     * @return Montagem Self instance
     */
    public function addItem($item, $formacoes = [])
    {
        $item_index = count($this->itens);
        // reindexa as formações para facilitar a busca na validação
        $formacoes_indexed = [];
        foreach ($formacoes as $formacao) {
            if ($formacao->getTipo() == Formacao::TIPO_COMPOSICAO) {
                $formacoes_indexed['c' . $formacao->getComposicaoID()] = $formacao;
            } else {
                $formacoes_indexed['p' . $formacao->getPacoteID()] = $formacao;
            }
        }
        $this->itens[$item_index] = [
            'item' => $item,
            'formacoes' => $formacoes_indexed
        ];
        $pacote_principal = null;
        $preco_composicoes = 0;
        $composicoes = [];
        foreach ($formacoes as $formacao) {
            if ($formacao->getTipo() == Formacao::TIPO_COMPOSICAO) {
                $composicao = $formacao->findComposicaoID();
                // TODO: verificar se a composição está ativa e não é uma composição básica
                if ($composicao->getTipo() == Composicao::TIPO_OPCIONAL) {
                    $preco_composicoes -= $composicao->getValor();
                } else {
                    $preco_composicoes += $composicao->getValor();
                }
                $composicoes[] = $composicao;
            } else {
                $pacote = $formacao->findPacoteID();
                if (is_null($pacote->getPropriedadeID())) {
                    if ($pacote_principal !== null) {
                        throw new \Exception('Muitas formações para o item principal');
                    }
                    $pacote_principal = $pacote;
                    continue;
                }
                $this->addPacote($pacote, ['itens' => [], 'preco' => 0], $item_index);
            }
        }
        if (!isset($pacote_principal)) {
            if (count($composicoes) > 0) {
                throw new \Exception('Propriedade não deve conter composições');
            }
            // ignora o pacote principal quando não tem propriedades
            if (count($formacoes) == 0 && !is_null($item->getProdutoPedidoID())) {
                $produto = $item->findProdutoID();
                throw new \Exception(
                    sprintf(
                        'Nenhuma formação encontrada para o produto "%s"',
                        $produto->getDescricao()
                    )
                );
            }
            return $this;
        }
        $this->addPacote(
            $pacote_principal,
            ['itens' => $composicoes, 'preco' => $preco_composicoes],
            $item_index
        );
        return $this;
    }

    /**
     * Carrega os grupos montando a estrutura para validação e filtragem do pacote
     */
    public function initialize()
    {
        $this->setGrupos(Grupo::findAll([
            'produtoid' => $this->getProdutoID()
        ]));
    }

    /**
     * Calcula as quantidades e somatórios de cada pacote e grupo formado com base no item do pedido
     */
    protected function quantify()
    {
        foreach ($this->grupos as $grupo_index => $grupo_formado) {
            $grupo = $grupo_formado['grupo'];
            $pacotes = $grupo_formado['pacotes'];
            $acumulado = 0;
            foreach ($pacotes as $pacote_index => $produto_formado) {
                $pacote = $produto_formado['pacote'];
                $item_index = $produto_formado['item_index'];
                $item = $this->itens[$item_index]['item'];
                $preco = $produto_formado['preco'];
                $minimo = $grupo_formado['minimo'];
                $quantidade = round($item->getQuantidade() / $minimo);
                $quantidade = max($quantidade, $pacote->getQuantidadeMinima());
                if ($pacote->getQuantidadeMaxima() > 0) {
                    $quantidade = min($quantidade, $pacote->getQuantidadeMaxima());
                }
                $acumulado += $quantidade;
                $this->grupos[$grupo_index]['pacotes'][$pacote_index]['quantidade'] = $quantidade;
            }
            $this->grupos[$grupo_index]['quantidade'] = $acumulado;
        }
    }

    /**
     * Verifica a formação de um item e retorna a descrição dele
     * @param int $index índice do item que será verificado suas formações
     * @param array $pacotes lista de pacotes formados que será verificado
     * @param \Closure $callback função que será chamada para cada formação do item
     * @return string descrição do item principal ou nulo para os outros itens
     */
    private function checkFormacao($index, $pacotes, $callback)
    {
        $descricao = null;
        $formacoes = $this->itens[$index]['formacoes'];
        foreach ($pacotes as $produto_formado) {
            $composicoes = $produto_formado['composicoes'];
            $pacote = $produto_formado['pacote'];
            foreach ($composicoes as $composicao) {
                $key = 'c' . $composicao->getID();
                if (!isset($formacoes[$key])) {
                    $produto_composicao = $composicao->findProdutoID();
                    $produto = $pacote->findProdutoID();
                    $produto_pacote = $this->findProdutoID();
                    throw new \Exception(
                        sprintf(
                            'A formação da composição "%s" não foi informada no produto "%s" do pacote "%s"',
                            $produto_composicao->getAbreviado(),
                            $produto->getDescricao(),
                            $produto_pacote->getDescricao()
                        ),
                        401
                    );
                }
                $formacao = $formacoes[$key];
                $callback($formacao, 1);
                unset($formacoes[$key]);
            }
            $key = 'p' . $pacote->getID();
            if (!isset($formacoes[$key])) {
                if (is_null($pacote->getPropriedadeID())) {
                    $msg = 'A formação do produto "%s" não foi informado no pacote "%s"';
                    $item_descricao = $produto->getDescricao();
                } else {
                    $msg = 'A formação da propriedade "%s" não foi informado no pacote "%s"';
                    $propriedade = $pacote->findPropriedadeID();
                    $item_descricao = $propriedade->getNome();
                }
                $produto_pacote = $this->findProdutoID();
                throw new \Exception(
                    sprintf(
                        $msg,
                        $item_descricao,
                        $produto_pacote->getDescricao()
                    ),
                    401
                );
            }
            $multiplicador = $produto_formado['quantidade'];
            $formacao = $formacoes[$key];
            $callback($formacao, $multiplicador);
            unset($formacoes[$key]);
            if ($pacote->getPropriedadeID() !== null) {
                $propriedade = $pacote->findPropriedadeID();
                $descricao .= ' ' . $propriedade->getAbreviado();
            }
        }
        if (count($formacoes)) {
            throw new \Exception('Formação excedente no pacote', 401);
        }
        return $descricao;
    }

    /**
     * Atualiza as informações do item com base na formação
     * @param int $index índice do item que será atualizado suas informações
     * @param float $quantidade quantidade calculada do item
     * @param float $preco preço de venda calculado do item
     * @param array $pacotes lista de pacotes formados
     */
    private function updateItem($index, $quantidade, $preco, $pacotes)
    {
        $item = $this->itens[$index]['item'];
        $item->setQuantidade($quantidade);
        $item->setPreco($preco);
        $item->setPrecoVenda($preco);
        $descricao = $this->checkFormacao($index, $pacotes, function ($formacao, $quantidade) {
            // corrige a formação
            $formacao->setQuantidade($quantidade);
        });
        if ($index == 0) {
            $produto = $item->findProdutoID();
            $item->setDescricao($produto->getDescricao() . $descricao);
        }
    }

    /**
     * Filtra o pacote montado ajustando as quantidade e valores
     * @param \Closure $callback função que será chamada para atribuir o preço e quantidade no item
     */
    public function filter($callback = null)
    {
        if ($callback === null) {
            $callback = [$this, 'updateItem'];
        }
        $this->quantify();
        // inicia o item principal
        $produto = $this->findProdutoID();
        $preco_principal = $produto->getPrecoVenda();
        $produtos_formados = [];
        foreach ($this->grupos as $grupo_formado) {
            $grupo = $grupo_formado['grupo'];
            $pacotes = $grupo_formado['pacotes'];
            $acumulado = $grupo_formado['quantidade'];
            foreach ($pacotes as $produto_formado) {
                $pacote = $produto_formado['pacote'];
                $preco = $produto_formado['preco'];
                if ($grupo->getFuncao() == Grupo::FUNCAO_MAXIMO) {
                    $preco = $grupo_formado['maior'];
                } elseif ($grupo->getFuncao() == Grupo::FUNCAO_MINIMO) {
                    $preco = $grupo_formado['menor'];
                }
                $multiplicador = $produto_formado['quantidade'];
                if ($grupo->getTipo() == Grupo::TIPO_FRACIONADO) {
                    $multiplicador = $multiplicador / $acumulado;
                }
                $quantidade = $multiplicador * $this->getQuantidade();
                if ($pacote->getPropriedadeID() === null) {
                    $item_index = $produto_formado['item_index'];
                    $callback($item_index, $quantidade, $preco, [$produto_formado]);
                } else {
                    $preco_principal += $preco * $multiplicador;
                    $produtos_formados[] = $produto_formado;
                }
            }
            if ($grupo->getTipo() == Grupo::TIPO_FRACIONADO) {
                $quantidade = count($pacotes);
            } else {
                $quantidade = $acumulado;
            }
            if ($quantidade < $grupo->getQuantidadeMinima()) {
                throw new \Exception(
                    sprintf(
                        'Necessário no mínimo %d itens, ' .
                            'mas apenas %d foram selecionados no grupo "%s" do pacote "%s"',
                        $grupo->getQuantidadeMinima(),
                        $quantidade,
                        $grupo->getDescricao(),
                        $produto->getDescricao()
                    ),
                    401
                );
            }
            if ($quantidade > $grupo->getQuantidadeMaxima() && $grupo->getQuantidadeMaxima() > 0) {
                throw new \Exception(
                    sprintf(
                        'O máximo de itens para o grupo "%s" é %d, foram selecionados %d no pacote "%s"',
                        $grupo->getDescricao(),
                        $grupo->getQuantidadeMaxima(),
                        $quantidade,
                        $produto->getDescricao()
                    ),
                    401
                );
            }
        }
        // aplica o preço no item principal
        $callback(0, $this->getQuantidade(), $preco_principal, $produtos_formados);
    }

    /**
     * Valida a montagem do pacote e lança erros casos existam
     */
    public function validate()
    {
        $this->filter(function ($index, $quantidade, $preco, $pacotes) {
            $item = $this->itens[$index]['item'];
            if (!is_equal($item->getQuantidade(), $quantidade, 0.00005)) {
                $produto = $item->findProdutoID();
                throw new \Exception(
                    sprintf(
                        'A quantidade do item "%s" deveria ser %s em vez de %s',
                        $produto->getDescricao(),
                        $quantidade,
                        $item->getQuantidade()
                    ),
                    401
                );
            }
            if (!is_equal($item->getPreco(), $preco)) {
                $produto = $item->findProdutoID();
                throw new \Exception(
                    sprintf(
                        'O preço do item "%s" deveria ser %s em vez de %s',
                        $produto->getDescricao(),
                        \MZ\Util\Mask::money($preco, true),
                        \MZ\Util\Mask::money($item->getPreco(), true)
                    ),
                    401
                );
            }
            if (!is_equal($item->getPrecoVenda(), $preco)) {
                $produto = $item->findProdutoID();
                throw new \Exception(
                    sprintf(
                        'O preço de venda do item "%s" deveria ser %s em vez de %s',
                        $produto->getDescricao(),
                        \MZ\Util\Mask::money($preco, true),
                        \MZ\Util\Mask::money($item->getPrecoVenda(), true)
                    ),
                    401
                );
            }
            $descricao = $this->checkFormacao($index, $pacotes, function ($formacao, $quantidade) {
                if (!is_equal($formacao->getQuantidade(), $quantidade, 0.00005)) {
                    throw new \Exception('A quantidade da formação está incorreta', 401);
                }
            });
            if ($index == 0) {
                $produto = $item->findProdutoID();
                $descricao = $produto->getDescricao() . $descricao;
                if ($descricao != $item->getDescricao()) {
                    throw new \Exception(
                        sprintf(
                            'A descrição do pacote "%s" está diferente do esperado "%s"',
                            $item->getDescricao(),
                            $descricao
                        ),
                        401
                    );
                }
            }
        });
        $errors = [];
        if (!is_null($this->getProdutoPedidoID())) {
            $errors['produtopedidoid'] = 'O pacote principal não pode fazer parte de outro';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }
}

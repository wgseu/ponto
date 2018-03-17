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
namespace MZ\Association;

use MZ\Product\Pacote;

class Product
{
    /**
     * Integration
     */
    private $integracao;
    /**
     * Dados
     */
    private $dados;
    /**
     * Produtos
     */
    private $produtos;

    public function __construct($integracao) {
        $this->integracao = $integracao;
        $this->dados = $this->integracao->read();
        $this->produtos = isset($this->dados['produtos'])?$this->dados['produtos']:array();
    }

    public function getProdutos()
    {
        return $this->produtos;
    }

    public function getDados()
    {
        return $this->dados;
    }

    public function populate($filename)
    {
        $dom = new \DOMDocument();
        if ($dom->load($filename) === false) {
            throw new \Exception('Falha ao carregar XML', 401);
        }
        return $this->populateFromXML($dom);
    }

    public function populateFromData($data)
    {
        $dom = new \DOMDocument();
        if ($dom->loadXML($data) === false) {
            throw new \Exception('Falha ao carregar XML', 401);
        }
        return $this->populateFromXML($dom);
    }

    public function populateFromXML($dom)
    {
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
                if (isset($this->produtos[$codigo_pai])) {
                    if (isset($this->produtos[$codigo]['id'])) {
                        $produto['id'] = $this->produtos[$codigo]['id'];
                        unset($this->produtos[$codigo]);
                    }
                    unset($produto['itens']);
                    if (isset($this->produtos[$codigo_pai]['itens'][$codigo])) {
                        $this->produtos[$codigo_pai]['itens'][$codigo] = array_merge(
                            $produto,
                            array_merge(
                                $this->produtos[$codigo_pai]['itens'][$codigo],
                                array('descricao' => $descricao)
                            )
                        );
                    } else {
                        $this->produtos[$codigo_pai]['itens'][$codigo] = $produto;
                    }
                } else {
                    unset($produto['codigo_pai']);
                    if (isset($this->produtos[$codigo])) {
                        $this->produtos[$codigo] = array_merge(
                            $produto,
                            array_merge(
                                $this->produtos[$codigo],
                                array('descricao' => $descricao)
                            )
                        );
                    } else {
                        $this->produtos[$codigo] = $produto;
                    }
                }
            }
        }
        $this->dados = isset($this->dados)?$this->dados:array();
        $this->dados['produtos'] = $this->produtos;
        $this->integracao->write($this->dados);
        return $this;
    }

    public function update($codigo, $id)
    {
        if (is_null($codigo) || is_null($id)) {
            throw new \Exception('Código ou ID inválido', 401);
        }
        if (!isset($this->produtos[$codigo])) {
            throw new \Exception('O produto informado não existe', 404);
        }
        $this->produtos[$codigo]['id'] = $id;
        $this->dados = isset($this->dados)?$this->dados:array();
        $this->dados['produtos'] = $this->produtos;
        $this->integracao->write($this->dados);
        try {
            $appsync = new \AppSync();
            $appsync->integratorChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        return $this;
    }

    public function delete($codigo, $subcodigo)
    {
        if (is_null($codigo)) {
            throw new \Exception('Código inválido ou não informado', 401);
        }
        if (!isset($this->produtos[$codigo])) {
            throw new \Exception('O produto informado não existe', 404);
        }
        if (!is_null($subcodigo) && !isset($this->produtos[$codigo]['itens'][$subcodigo])) {
            throw new \Exception('O item informado não existe no pacote', 404);
        }
        if (isset($subcodigo)) {
            unset($this->produtos[$codigo]['itens'][$subcodigo]);
        } else {
            unset($this->produtos[$codigo]);
        }
        $this->dados = isset($this->dados)?$this->dados:array();
        $this->dados['produtos'] = $this->produtos;
        $this->integracao->write($this->dados);
        try {
            $appsync = new \AppSync();
            $appsync->integratorChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        return $this;
    }

    public function mount($codigo, $subcodigo, $id)
    {
        if (is_null($codigo) || is_null($id)) {
            throw new \Exception('Código ou ID inválido', 401);
        }
        if (!isset($this->produtos[$codigo])) {
            throw new \Exception('O produto informado não existe', 404);
        }
        if (!isset($this->produtos[$codigo]['itens'][$subcodigo])) {
            throw new \Exception('O item do pacote não existe', 404);
        }
        $this->produtos[$codigo]['itens'][$subcodigo]['id'] = $id;
        $this->dados = isset($this->dados)?$this->dados:array();
        $this->dados['produtos'] = $this->produtos;
        $this->integracao->write($this->dados);
        try {
            $appsync = new \AppSync();
            $appsync->integratorChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        return $this;
    }
    
    public function findPackage($codigo)
    {
        if (is_null($codigo)) {
            throw new \Exception('Código não informado', 401);
        }
        if (!isset($this->produtos[$codigo])) {
            throw new \Exception('O produto informado não existe', 404);
        }
        $produto = $this->produtos[$codigo];
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
            $qtd_pacotes = Pacote::count(
                array('visivel' => 'Y', 'grupoid' => $grupo->getID())
            );
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
                $subassociado = Pacote::findByID(
                    isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']
                );
                if ($subassociado->getPacoteID() != $associado->getID()) {
                    $subassociado = new Pacote();
                }
                $grupoid = intval($subassociado->getGrupoID());
                if (($total_igual || count($grupos) == 1) && $grupo_index < count($grupos) && $grupoid == 0) {
                    $grupoid = $grupos[$grupo_index]['id'];
                }
                $produto['itens'][$subcodigo]['grupoid'] = $grupoid;
                $total_pacotes++;
                if (
                    $grupo_index < count($contagem) &&
                    $total_pacotes == $contagem[$grupo_index] &&
                    $grupo_index < count($grupos) - 1)
                {
                    $grupo_index++;
                    $total_pacotes = 0;
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
                $produto['itens'][$subcodigo]['grupoid'] = 0;
                $item = \ZProduto::getPeloID($subassociado->getProdutoID());
            }
            $produto['itens'][$subcodigo]['associado'] = $item->toArray();
        }
        return array('produto' => $produto, 'grupos' => $grupos);
    }

    public function findAll()
    {
        $produtos = $this->produtos;
        foreach ($produtos as $codigo => $produto) {
            $associado = \ZProduto::getPeloID(isset($produto['id'])?$produto['id']:$produto['codigo_pdv']);
            $produtos[$codigo]['produto'] = $associado;
            $associados = 0;
            foreach ($produto['itens'] as $subcodigo => $subproduto) {
                if ($associado->getTipo() == \ProdutoTipo::PACOTE) {
                    $subassociado = Pacote::findByID(isset($subproduto['id'])?$subproduto['id']:$subproduto['codigo_pdv']);
                    if ($subassociado->getPacoteID() != $associado->getID()) {
                        $subassociado = new Pacote();
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
        return $produtos;
    }
}
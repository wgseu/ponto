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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Product;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informações sobre o produto, composição ou pacote
 */
class Produto extends \MZ\Database\Helper
{

    /**
     * Informa qual é o tipo de produto. Produto: Produto normal que possui
     * estoque, Composição: Produto que não possui estoque diretamente, pois é
     * composto de outros produtos ou composições, Pacote: Permite a composição
     * no momento da venda, não possui estoque diretamente
     */
    const TIPO_PRODUTO = 'Produto';
    const TIPO_COMPOSICAO = 'Composicao';
    const TIPO_PACOTE = 'Pacote';

    /**
     * Código do produto
     */
    private $id;
    /**
     * Código de barras do produto, deve ser único entre todos os produtos
     */
    private $codigo_barras;
    /**
     * Categoria do produto, permite a rápida localização ao utilizar tablets
     */
    private $categoria_id;
    /**
     * Informa a unidade do produtos, Ex.: Grama, Litro.
     */
    private $unidade_id;
    /**
     * Informa de qual setor o produto será retirado após a venda
     */
    private $setor_estoque_id;
    /**
     * Informa em qual setor de preparo será enviado o ticket de preparo ou
     * autorização, se nenhum for informado nada será impresso
     */
    private $setor_preparo_id;
    /**
     * Informações de tributação do produto
     */
    private $tributacao_id;
    /**
     * Descrição do produto, Ex.: Refri. Coca Cola 2L.
     */
    private $descricao;
    /**
     * Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo
     */
    private $abreviacao;
    /**
     * Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano
     */
    private $detalhes;
    /**
     * Informa a quantidade limite para que o sistema avise que o produto já
     * está acabando
     */
    private $quantidade_limite;
    /**
     * Informa a quantidade máxima do produto no estoque, não proibe, apenas
     * avisa
     */
    private $quantidade_maxima;
    /**
     * Informa o conteúdo do produto, Ex.: 2000 para 2L de conteúdo, 200 para
     * 200g de peso ou 1 para 1 unidade
     */
    private $conteudo;
    /**
     * Preço de venda ou preço de venda base para pacotes
     */
    private $preco_venda;
    /**
     * Informa qual o valor para o custo de produção do produto, utilizado
     * quando não há formação de composição do produto
     */
    private $custo_producao;
    /**
     * Informa qual é o tipo de produto. Produto: Produto normal que possui
     * estoque, Composição: Produto que não possui estoque diretamente, pois é
     * composto de outros produtos ou composições, Pacote: Permite a composição
     * no momento da venda, não possui estoque diretamente
     */
    private $tipo;
    /**
     * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este
     * produto
     */
    private $cobrar_servico;
    /**
     * Informa se o produto pode ser vendido fracionado
     */
    private $divisivel;
    /**
     * Informa se o peso do produto deve ser obtido de uma balança,
     * obrigatoriamente o produto deve ser divisível
     */
    private $pesavel;
    /**
     * Informa se o produto vence em pouco tempo
     */
    private $perecivel;
    /**
     * Tempo de preparo em minutos para preparar uma composição, 0 para não
     * informado
     */
    private $tempo_preparo;
    /**
     * Informa se o produto estará disponível para venda
     */
    private $visivel;
    /**
     * Imagem do produto
     */
    private $imagem;
    /**
     * Data de atualização das informações do produto
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Produto
     * @param array $produto All field and values to fill the instance
     */
    public function __construct($produto = [])
    {
        parent::__construct($produto);
    }

    /**
     * Código do produto
     * @return mixed ID of Produto
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Produto Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código de barras do produto, deve ser único entre todos os produtos
     * @return mixed Código de barras of Produto
     */
    public function getCodigoBarras()
    {
        return $this->codigo_barras;
    }

    /**
     * Set CodigoBarras value to new on param
     * @param  mixed $codigo_barras new value for CodigoBarras
     * @return Produto Self instance
     */
    public function setCodigoBarras($codigo_barras)
    {
        $this->codigo_barras = $codigo_barras;
        return $this;
    }

    /**
     * Categoria do produto, permite a rápida localização ao utilizar tablets
     * @return mixed Categoria of Produto
     */
    public function getCategoriaID()
    {
        return $this->categoria_id;
    }

    /**
     * Set CategoriaID value to new on param
     * @param  mixed $categoria_id new value for CategoriaID
     * @return Produto Self instance
     */
    public function setCategoriaID($categoria_id)
    {
        $this->categoria_id = $categoria_id;
        return $this;
    }

    /**
     * Informa a unidade do produtos, Ex.: Grama, Litro.
     * @return mixed Unidade of Produto
     */
    public function getUnidadeID()
    {
        return $this->unidade_id;
    }

    /**
     * Set UnidadeID value to new on param
     * @param  mixed $unidade_id new value for UnidadeID
     * @return Produto Self instance
     */
    public function setUnidadeID($unidade_id)
    {
        $this->unidade_id = $unidade_id;
        return $this;
    }

    /**
     * Informa de qual setor o produto será retirado após a venda
     * @return mixed Setor de estoque of Produto
     */
    public function getSetorEstoqueID()
    {
        return $this->setor_estoque_id;
    }

    /**
     * Set SetorEstoqueID value to new on param
     * @param  mixed $setor_estoque_id new value for SetorEstoqueID
     * @return Produto Self instance
     */
    public function setSetorEstoqueID($setor_estoque_id)
    {
        $this->setor_estoque_id = $setor_estoque_id;
        return $this;
    }

    /**
     * Informa em qual setor de preparo será enviado o ticket de preparo ou
     * autorização, se nenhum for informado nada será impresso
     * @return mixed Setor de preparo of Produto
     */
    public function getSetorPreparoID()
    {
        return $this->setor_preparo_id;
    }

    /**
     * Set SetorPreparoID value to new on param
     * @param  mixed $setor_preparo_id new value for SetorPreparoID
     * @return Produto Self instance
     */
    public function setSetorPreparoID($setor_preparo_id)
    {
        $this->setor_preparo_id = $setor_preparo_id;
        return $this;
    }

    /**
     * Informações de tributação do produto
     * @return mixed Tributação of Produto
     */
    public function getTributacaoID()
    {
        return $this->tributacao_id;
    }

    /**
     * Set TributacaoID value to new on param
     * @param  mixed $tributacao_id new value for TributacaoID
     * @return Produto Self instance
     */
    public function setTributacaoID($tributacao_id)
    {
        $this->tributacao_id = $tributacao_id;
        return $this;
    }

    /**
     * Descrição do produto, Ex.: Refri. Coca Cola 2L.
     * @return mixed Descrição of Produto
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Produto Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo
     * @return mixed Abreviação of Produto
     */
    public function getAbreviacao()
    {
        return $this->abreviacao;
    }

    /**
     * Set Abreviacao value to new on param
     * @param  mixed $abreviacao new value for Abreviacao
     * @return Produto Self instance
     */
    public function setAbreviacao($abreviacao)
    {
        $this->abreviacao = $abreviacao;
        return $this;
    }

    /**
     * Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano
     * @return mixed Detalhes of Produto
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return Produto Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa a quantidade limite para que o sistema avise que o produto já
     * está acabando
     * @return mixed Quantidade limite of Produto
     */
    public function getQuantidadeLimite()
    {
        return $this->quantidade_limite;
    }

    /**
     * Set QuantidadeLimite value to new on param
     * @param  mixed $quantidade_limite new value for QuantidadeLimite
     * @return Produto Self instance
     */
    public function setQuantidadeLimite($quantidade_limite)
    {
        $this->quantidade_limite = $quantidade_limite;
        return $this;
    }

    /**
     * Informa a quantidade máxima do produto no estoque, não proibe, apenas
     * avisa
     * @return mixed Quantidade máxima of Produto
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param  mixed $quantidade_maxima new value for QuantidadeMaxima
     * @return Produto Self instance
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
        return $this;
    }

    /**
     * Informa o conteúdo do produto, Ex.: 2000 para 2L de conteúdo, 200 para
     * 200g de peso ou 1 para 1 unidade
     * @return mixed Conteúdo of Produto
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Set Conteudo value to new on param
     * @param  mixed $conteudo new value for Conteudo
     * @return Produto Self instance
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    /**
     * Preço de venda ou preço de venda base para pacotes
     * @return mixed Preço de venda of Produto
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    /**
     * Set PrecoVenda value to new on param
     * @param  mixed $preco_venda new value for PrecoVenda
     * @return Produto Self instance
     */
    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
        return $this;
    }

    /**
     * Informa qual o valor para o custo de produção do produto, utilizado
     * quando não há formação de composição do produto
     * @return mixed Custo de produção of Produto
     */
    public function getCustoProducao()
    {
        return $this->custo_producao;
    }

    /**
     * Set CustoProducao value to new on param
     * @param  mixed $custo_producao new value for CustoProducao
     * @return Produto Self instance
     */
    public function setCustoProducao($custo_producao)
    {
        $this->custo_producao = $custo_producao;
        return $this;
    }

    /**
     * Informa qual é o tipo de produto. Produto: Produto normal que possui
     * estoque, Composição: Produto que não possui estoque diretamente, pois é
     * composto de outros produtos ou composições, Pacote: Permite a composição
     * no momento da venda, não possui estoque diretamente
     * @return mixed Tipo of Produto
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Produto Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este
     * produto
     * @return mixed Cobrança de serviço of Produto
     */
    public function getCobrarServico()
    {
        return $this->cobrar_servico;
    }

    /**
     * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este
     * produto
     * @return boolean Check if a of CobrarServico is selected or checked
     */
    public function isCobrarServico()
    {
        return $this->cobrar_servico == 'Y';
    }

    /**
     * Set CobrarServico value to new on param
     * @param  mixed $cobrar_servico new value for CobrarServico
     * @return Produto Self instance
     */
    public function setCobrarServico($cobrar_servico)
    {
        $this->cobrar_servico = $cobrar_servico;
        return $this;
    }

    /**
     * Informa se o produto pode ser vendido fracionado
     * @return mixed Divisível of Produto
     */
    public function getDivisivel()
    {
        return $this->divisivel;
    }

    /**
     * Informa se o produto pode ser vendido fracionado
     * @return boolean Check if o of Divisivel is selected or checked
     */
    public function isDivisivel()
    {
        return $this->divisivel == 'Y';
    }

    /**
     * Set Divisivel value to new on param
     * @param  mixed $divisivel new value for Divisivel
     * @return Produto Self instance
     */
    public function setDivisivel($divisivel)
    {
        $this->divisivel = $divisivel;
        return $this;
    }

    /**
     * Informa se o peso do produto deve ser obtido de uma balança,
     * obrigatoriamente o produto deve ser divisível
     * @return mixed Pesável of Produto
     */
    public function getPesavel()
    {
        return $this->pesavel;
    }

    /**
     * Informa se o peso do produto deve ser obtido de uma balança,
     * obrigatoriamente o produto deve ser divisível
     * @return boolean Check if o of Pesavel is selected or checked
     */
    public function isPesavel()
    {
        return $this->pesavel == 'Y';
    }

    /**
     * Set Pesavel value to new on param
     * @param  mixed $pesavel new value for Pesavel
     * @return Produto Self instance
     */
    public function setPesavel($pesavel)
    {
        $this->pesavel = $pesavel;
        return $this;
    }

    /**
     * Informa se o produto vence em pouco tempo
     * @return mixed Perecível of Produto
     */
    public function getPerecivel()
    {
        return $this->perecivel;
    }

    /**
     * Informa se o produto vence em pouco tempo
     * @return boolean Check if o of Perecivel is selected or checked
     */
    public function isPerecivel()
    {
        return $this->perecivel == 'Y';
    }

    /**
     * Set Perecivel value to new on param
     * @param  mixed $perecivel new value for Perecivel
     * @return Produto Self instance
     */
    public function setPerecivel($perecivel)
    {
        $this->perecivel = $perecivel;
        return $this;
    }

    /**
     * Tempo de preparo em minutos para preparar uma composição, 0 para não
     * informado
     * @return mixed Tempo de preparo of Produto
     */
    public function getTempoPreparo()
    {
        return $this->tempo_preparo;
    }

    /**
     * Set TempoPreparo value to new on param
     * @param  mixed $tempo_preparo new value for TempoPreparo
     * @return Produto Self instance
     */
    public function setTempoPreparo($tempo_preparo)
    {
        $this->tempo_preparo = $tempo_preparo;
        return $this;
    }

    /**
     * Informa se o produto estará disponível para venda
     * @return mixed Visível of Produto
     */
    public function getVisivel()
    {
        return $this->visivel;
    }

    /**
     * Informa se o produto estará disponível para venda
     * @return boolean Check if o of Visivel is selected or checked
     */
    public function isVisivel()
    {
        return $this->visivel == 'Y';
    }

    /**
     * Set Visivel value to new on param
     * @param  mixed $visivel new value for Visivel
     * @return Produto Self instance
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $visivel;
        return $this;
    }

    /**
     * Imagem do produto
     * @return mixed Imagem of Produto
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set Imagem value to new on param
     * @param  mixed $imagem new value for Imagem
     * @return Produto Self instance
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * Data de atualização das informações do produto
     * @return mixed Data de atualização of Produto
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return Produto Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $produto = parent::toArray($recursive);
        $produto['id'] = $this->getID();
        $produto['codigobarras'] = $this->getCodigoBarras();
        $produto['categoriaid'] = $this->getCategoriaID();
        $produto['unidadeid'] = $this->getUnidadeID();
        $produto['setorestoqueid'] = $this->getSetorEstoqueID();
        $produto['setorpreparoid'] = $this->getSetorPreparoID();
        $produto['tributacaoid'] = $this->getTributacaoID();
        $produto['descricao'] = $this->getDescricao();
        $produto['abreviacao'] = $this->getAbreviacao();
        $produto['detalhes'] = $this->getDetalhes();
        $produto['quantidadelimite'] = $this->getQuantidadeLimite();
        $produto['quantidademaxima'] = $this->getQuantidadeMaxima();
        $produto['conteudo'] = $this->getConteudo();
        $produto['precovenda'] = $this->getPrecoVenda();
        $produto['custoproducao'] = $this->getCustoProducao();
        $produto['tipo'] = $this->getTipo();
        $produto['cobrarservico'] = $this->getCobrarServico();
        $produto['divisivel'] = $this->getDivisivel();
        $produto['pesavel'] = $this->getPesavel();
        $produto['perecivel'] = $this->getPerecivel();
        $produto['tempopreparo'] = $this->getTempoPreparo();
        $produto['visivel'] = $this->getVisivel();
        $produto['imagem'] = $this->getImagem();
        $produto['dataatualizacao'] = $this->getDataAtualizacao();
        return $produto;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $produto Associated key -> value to assign into this instance
     * @return Produto Self instance
     */
    public function fromArray($produto = [])
    {
        if ($produto instanceof Produto) {
            $produto = $produto->toArray();
        } elseif (!is_array($produto)) {
            $produto = [];
        }
        parent::fromArray($produto);
        if (!isset($produto['id'])) {
            $this->setID(null);
        } else {
            $this->setID($produto['id']);
        }
        if (!array_key_exists('codigobarras', $produto)) {
            $this->setCodigoBarras(null);
        } else {
            $this->setCodigoBarras($produto['codigobarras']);
        }
        if (!isset($produto['categoriaid'])) {
            $this->setCategoriaID(null);
        } else {
            $this->setCategoriaID($produto['categoriaid']);
        }
        if (!isset($produto['unidadeid'])) {
            $this->setUnidadeID(null);
        } else {
            $this->setUnidadeID($produto['unidadeid']);
        }
        if (!array_key_exists('setorestoqueid', $produto)) {
            $this->setSetorEstoqueID(null);
        } else {
            $this->setSetorEstoqueID($produto['setorestoqueid']);
        }
        if (!array_key_exists('setorpreparoid', $produto)) {
            $this->setSetorPreparoID(null);
        } else {
            $this->setSetorPreparoID($produto['setorpreparoid']);
        }
        if (!array_key_exists('tributacaoid', $produto)) {
            $this->setTributacaoID(null);
        } else {
            $this->setTributacaoID($produto['tributacaoid']);
        }
        if (!isset($produto['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($produto['descricao']);
        }
        if (!array_key_exists('abreviacao', $produto)) {
            $this->setAbreviacao(null);
        } else {
            $this->setAbreviacao($produto['abreviacao']);
        }
        if (!array_key_exists('detalhes', $produto)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($produto['detalhes']);
        }
        if (!isset($produto['quantidadelimite'])) {
            $this->setQuantidadeLimite(null);
        } else {
            $this->setQuantidadeLimite($produto['quantidadelimite']);
        }
        if (!isset($produto['quantidademaxima'])) {
            $this->setQuantidadeMaxima(null);
        } else {
            $this->setQuantidadeMaxima($produto['quantidademaxima']);
        }
        if (!isset($produto['conteudo'])) {
            $this->setConteudo(null);
        } else {
            $this->setConteudo($produto['conteudo']);
        }
        if (!isset($produto['precovenda'])) {
            $this->setPrecoVenda(null);
        } else {
            $this->setPrecoVenda($produto['precovenda']);
        }
        if (!array_key_exists('custoproducao', $produto)) {
            $this->setCustoProducao(null);
        } else {
            $this->setCustoProducao($produto['custoproducao']);
        }
        if (!isset($produto['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($produto['tipo']);
        }
        if (!isset($produto['cobrarservico'])) {
            $this->setCobrarServico(null);
        } else {
            $this->setCobrarServico($produto['cobrarservico']);
        }
        if (!isset($produto['divisivel'])) {
            $this->setDivisivel(null);
        } else {
            $this->setDivisivel($produto['divisivel']);
        }
        if (!isset($produto['pesavel'])) {
            $this->setPesavel(null);
        } else {
            $this->setPesavel($produto['pesavel']);
        }
        if (!isset($produto['perecivel'])) {
            $this->setPerecivel(null);
        } else {
            $this->setPerecivel($produto['perecivel']);
        }
        if (!isset($produto['tempopreparo'])) {
            $this->setTempoPreparo(null);
        } else {
            $this->setTempoPreparo($produto['tempopreparo']);
        }
        if (!isset($produto['visivel'])) {
            $this->setVisivel(null);
        } else {
            $this->setVisivel($produto['visivel']);
        }
        if (!array_key_exists('imagem', $produto)) {
            $this->setImagem(null);
        } else {
            $this->setImagem($produto['imagem']);
        }
        if (!isset($produto['dataatualizacao'])) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($produto['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @return string relative web path for produto imagem
     */
    public function makeImagem($default = false)
    {
        $imagem = $this->getImagem();
        if ($default) {
            $imagem = null;
        }
        return get_image_url($imagem, 'produto', 'produto.png');
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $produto = parent::publish();
        $produto['imagem'] = $this->makeImagem();
        return $produto;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Produto $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCodigoBarras(Filter::string($this->getCodigoBarras()));
        $this->setCategoriaID(Filter::number($this->getCategoriaID()));
        $this->setUnidadeID(Filter::number($this->getUnidadeID()));
        $this->setSetorEstoqueID(Filter::number($this->getSetorEstoqueID()));
        $this->setSetorPreparoID(Filter::number($this->getSetorPreparoID()));
        $this->setTributacaoID(Filter::number($this->getTributacaoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setAbreviacao(Filter::string($this->getAbreviacao()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setQuantidadeLimite(Filter::float($this->getQuantidadeLimite()));
        $this->setQuantidadeMaxima(Filter::float($this->getQuantidadeMaxima()));
        $this->setConteudo(Filter::float($this->getConteudo()));
        $this->setPrecoVenda(Filter::money($this->getPrecoVenda()));
        $this->setCustoProducao(Filter::money($this->getCustoProducao()));
        $this->setTempoPreparo(Filter::number($this->getTempoPreparo()));
        $imagem = upload_image('raw_imagem', 'produto');
        if (is_null($imagem) && trim($this->getImagem()) != '') {
            $this->setImagem($original->getImagem());
        } else {
            $this->setImagem($imagem);
        }
        $this->setDataAtualizacao(Filter::datetime($this->getDataAtualizacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Produto $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagem()) && $dependency->getImagem() != $this->getImagem()) {
            @unlink(get_image_path($this->getImagem(), 'produto'));
        }
        $this->setImagem($dependency->getImagem());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Produto in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCategoriaID())) {
            $errors['categoriaid'] = 'A categoria não pode ser vazia';
        }
        if (is_null($this->getUnidadeID())) {
            $errors['unidadeid'] = 'A unidade não pode ser vazia';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getQuantidadeLimite())) {
            $errors['quantidadelimite'] = 'A quantidade limite não pode ser vazia';
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $errors['quantidademaxima'] = 'A quantidade máxima não pode ser vazia';
        }
        if (is_null($this->getConteudo())) {
            $errors['conteudo'] = 'O conteúdo não pode ser vazio';
        }
        if (is_null($this->getPrecoVenda())) {
            $errors['precovenda'] = 'O preço de venda não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions(), true)) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getCobrarServico())) {
            $errors['cobrarservico'] = 'A cobrança de serviço não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getCobrarServico(), true)) {
            $errors['cobrarservico'] = 'A cobrança de serviço é inválida';
        }
        if (is_null($this->getDivisivel())) {
            $errors['divisivel'] = 'O divisível não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getDivisivel(), true)) {
            $errors['divisivel'] = 'O divisível é inválido';
        }
        if (is_null($this->getPesavel())) {
            $errors['pesavel'] = 'O pesável não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getPesavel(), true)) {
            $errors['pesavel'] = 'O pesável é inválido';
        }
        if (is_null($this->getPerecivel())) {
            $errors['perecivel'] = 'O perecível não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getPerecivel(), true)) {
            $errors['perecivel'] = 'O perecível é inválido';
        }
        if (is_null($this->getTempoPreparo())) {
            $errors['tempopreparo'] = 'O tempo de preparo não pode ser vazio';
        }
        if (is_null($this->getVisivel())) {
            $errors['visivel'] = 'O visível não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getVisivel(), true)) {
            $errors['visivel'] = 'O visível é inválido';
        }
        if (is_null($this->getDataAtualizacao())) {
            $errors['dataatualizacao'] = 'A data de atualização não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'CodBarras_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'codigobarras' => sprintf(
                    'O código de barras "%s" já está cadastrado',
                    $this->getCodigoBarras()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Produto into the database and fill instance from database
     * @return Produto Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Produtos')->values($values)->execute();
            $produto = self::findByID($id);
            $this->fromArray($produto->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Produto with instance values into database for ID
     * @return Produto Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do produto não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Produtos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $produto = self::findByID($this->getID());
            $this->fromArray($produto->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do produto não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Produtos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Produto Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  int $id id to find Produto
     * @return Produto Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Load into this object from database using, Descricao
     * @param  string $descricao descrição to find Produto
     * @return Produto Self filled instance or empty when not found
     */
    public function loadByDescricao($descricao)
    {
        return $this->load([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Load into this object from database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Produto
     * @return Produto Self filled instance or empty when not found
     */
    public function loadByCodigoBarras($codigo_barras)
    {
        return $this->load([
            'codigobarras' => strval($codigo_barras),
        ]);
    }

    /**
     * Categoria do produto, permite a rápida localização ao utilizar tablets
     * @return \MZ\Product\Categoria The object fetched from database
     */
    public function findCategoriaID()
    {
        return \MZ\Product\Categoria::findByID($this->getCategoriaID());
    }

    /**
     * Informa a unidade do produtos, Ex.: Grama, Litro.
     * @return \MZ\Product\Unidade The object fetched from database
     */
    public function findUnidadeID()
    {
        return \MZ\Product\Unidade::findByID($this->getUnidadeID());
    }

    /**
     * Informa de qual setor o produto será retirado após a venda
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorEstoqueID()
    {
        if (is_null($this->getSetorEstoqueID())) {
            return new \MZ\Environment\Setor();
        }
        return \MZ\Environment\Setor::findByID($this->getSetorEstoqueID());
    }

    /**
     * Informa em qual setor de preparo será enviado o ticket de preparo ou
     * autorização, se nenhum for informado nada será impresso
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorPreparoID()
    {
        if (is_null($this->getSetorPreparoID())) {
            return new \MZ\Environment\Setor();
        }
        return \MZ\Environment\Setor::findByID($this->getSetorPreparoID());
    }

    /**
     * Informações de tributação do produto
     * @return \MZ\Invoice\Tributacao The object fetched from database
     */
    public function findTributacaoID()
    {
        if (is_null($this->getTributacaoID())) {
            return new \MZ\Invoice\Tributacao();
        }
        return \MZ\Invoice\Tributacao::findByID($this->getTributacaoID());
    }

    /**
     * Gets textual and translated Tipo for Produto
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_PRODUTO => 'Produto',
            self::TIPO_COMPOSICAO => 'Composição',
            self::TIPO_PACOTE => 'Pacote',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $produto = new Produto();
        $allowed = Filter::concatKeys('p.', $produto->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'p.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Produtos p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.descricao ASC');
        $query = $query->orderBy('p.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Produto A filled Produto or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Produto($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Produto
     * @return Produto A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Produto
     * @return Produto A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        return self::find([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Find this object on database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Produto
     * @return Produto A filled instance or empty when not found
     */
    public static function findByCodigoBarras($codigo_barras)
    {
        return self::find([
            'codigobarras' => strval($codigo_barras),
        ]);
    }

    /**
     * Find all Produto
     * @param  array  $condition Condition to get all Produto
     * @param  array  $order     Order Produto
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Produto
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Produto($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}

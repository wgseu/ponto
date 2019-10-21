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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa qual foi a formação que gerou esse produto, assim como quais
 * item foram retirados/adicionados da composição
 */
class Formacao extends SyncModel
{

    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     */
    const TIPO_PACOTE = 'Pacote';
    const TIPO_COMPOSICAO = 'Composicao';

    /**
     * Identificador da formação
     */
    private $id;
    /**
     * Informa qual foi o produto vendido para essa formação
     */
    private $item_id;
    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     */
    private $tipo;
    /**
     * Informa qual pacote foi selecionado no momento da venda
     */
    private $pacote_id;
    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     */
    private $composicao_id;
    /**
     * Quantidade de itens selecionados
     */
    private $quantidade;

    /**
     * Constructor for a new empty instance of Formacao
     * @param array $formacao All field and values to fill the instance
     */
    public function __construct($formacao = [])
    {
        parent::__construct($formacao);
    }

    /**
     * Identificador da formação
     * @return int id of Formação
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Formação
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa qual foi o produto vendido para essa formação
     * @return int item do pedido of Formação
     */
    public function getItemID()
    {
        return $this->item_id;
    }

    /**
     * Set ItemID value to new on param
     * @param int $item_id Set item do pedido for Formação
     * @return self Self instance
     */
    public function setItemID($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     * @return string tipo of Formação
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Formação
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa qual pacote foi selecionado no momento da venda
     * @return int pacote of Formação
     */
    public function getPacoteID()
    {
        return $this->pacote_id;
    }

    /**
     * Set PacoteID value to new on param
     * @param int $pacote_id Set pacote for Formação
     * @return self Self instance
     */
    public function setPacoteID($pacote_id)
    {
        $this->pacote_id = $pacote_id;
        return $this;
    }

    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     * @return int composição of Formação
     */
    public function getComposicaoID()
    {
        return $this->composicao_id;
    }

    /**
     * Set ComposicaoID value to new on param
     * @param int $composicao_id Set composição for Formação
     * @return self Self instance
     */
    public function setComposicaoID($composicao_id)
    {
        $this->composicao_id = $composicao_id;
        return $this;
    }

    /**
     * Quantidade de itens selecionados
     * @return float quantidade of Formação
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Formação
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $formacao = parent::toArray($recursive);
        $formacao['id'] = $this->getID();
        $formacao['itemid'] = $this->getItemID();
        $formacao['tipo'] = $this->getTipo();
        $formacao['pacoteid'] = $this->getPacoteID();
        $formacao['composicaoid'] = $this->getComposicaoID();
        $formacao['quantidade'] = $this->getQuantidade();
        return $formacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $formacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($formacao = [])
    {
        if ($formacao instanceof self) {
            $formacao = $formacao->toArray();
        } elseif (!is_array($formacao)) {
            $formacao = [];
        }
        parent::fromArray($formacao);
        if (!isset($formacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($formacao['id']);
        }
        if (!isset($formacao['itemid'])) {
            $this->setItemID(null);
        } else {
            $this->setItemID($formacao['itemid']);
        }
        if (!isset($formacao['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($formacao['tipo']);
        }
        if (!array_key_exists('pacoteid', $formacao)) {
            $this->setPacoteID(null);
        } else {
            $this->setPacoteID($formacao['pacoteid']);
        }
        if (!array_key_exists('composicaoid', $formacao)) {
            $this->setComposicaoID(null);
        } else {
            $this->setComposicaoID($formacao['composicaoid']);
        }
        if (!isset($formacao['quantidade'])) {
            $this->setQuantidade(1);
        } else {
            $this->setQuantidade($formacao['quantidade']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $formacao = parent::publish($requester);
        return $formacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setItemID(Filter::number($this->getItemID()));
        $this->setPacoteID(Filter::number($this->getPacoteID()));
        $this->setComposicaoID(Filter::number($this->getComposicaoID()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Formacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getItemID())) {
            $errors['itemid'] = _t('formacao.item_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('formacao.tipo_invalid');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('formacao.quantidade_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['ItemID', 'PacoteID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'itemid' => _t(
                    'formacao.item_id_used',
                    $this->getItemID()
                ),
                'pacoteid' => _t(
                    'formacao.pacote_id_used',
                    $this->getPacoteID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, ItemID, PacoteID
     * @return self Self filled instance or empty when not found
     */
    public function loadByItemIDPacoteID()
    {
        return $this->load([
            'itemid' => intval($this->getItemID()),
            'pacoteid' => intval($this->getPacoteID()),
        ]);
    }

    /**
     * Informa qual foi o produto vendido para essa formação
     * @return \MZ\Sale\Item The object fetched from database
     */
    public function findItemID()
    {
        return \MZ\Sale\Item::findByID($this->getItemID());
    }

    /**
     * Informa qual pacote foi selecionado no momento da venda
     * @return \MZ\Product\Pacote The object fetched from database
     */
    public function findPacoteID()
    {
        return \MZ\Product\Pacote::findByID($this->getPacoteID());
    }

    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     * @return \MZ\Product\Composicao The object fetched from database
     */
    public function findComposicaoID()
    {
        return \MZ\Product\Composicao::findByID($this->getComposicaoID());
    }

    /**
     * Gets textual and translated Tipo for Formacao
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_PACOTE => _t('formacao.tipo_pacote'),
            self::TIPO_COMPOSICAO => _t('formacao.tipo_composicao'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'f.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Formacoes f');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, ItemID, PacoteID
     * @param int $item_id item do pedido to find Formação
     * @param int $pacote_id pacote to find Formação
     * @return self A filled instance or empty when not found
     */
    public static function findByItemIDPacoteID($item_id, $pacote_id)
    {
        $result = new self();
        $result->setItemID($item_id);
        $result->setPacoteID($pacote_id);
        return $result->loadByItemIDPacoteID();
    }
}
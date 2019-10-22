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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa todos os valores nutricionais da tabela nutricional
 */
class ValorNutricional extends SyncModel
{

    /**
     * Identificador do valor nutricional
     */
    private $id;
    /**
     * Informe a que tabela nutricional este valor pertence
     */
    private $informacao_id;
    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     */
    private $unidade_id;
    /**
     * Nome do valor nutricional
     */
    private $nome;
    /**
     * Quantidade do valor nutricional com base na porção
     */
    private $quantidade;
    /**
     * Valor diário em %
     */
    private $valor_diario;

    /**
     * Constructor for a new empty instance of ValorNutricional
     * @param array $valor_nutricional All field and values to fill the instance
     */
    public function __construct($valor_nutricional = [])
    {
        parent::__construct($valor_nutricional);
    }

    /**
     * Identificador do valor nutricional
     * @return int id of Valor nutricional
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Valor nutricional
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informe a que tabela nutricional este valor pertence
     * @return int informação of Valor nutricional
     */
    public function getInformacaoID()
    {
        return $this->informacao_id;
    }

    /**
     * Set InformacaoID value to new on param
     * @param int $informacao_id Set informação for Valor nutricional
     * @return self Self instance
     */
    public function setInformacaoID($informacao_id)
    {
        $this->informacao_id = $informacao_id;
        return $this;
    }

    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     * @return int unidade of Valor nutricional
     */
    public function getUnidadeID()
    {
        return $this->unidade_id;
    }

    /**
     * Set UnidadeID value to new on param
     * @param int $unidade_id Set unidade for Valor nutricional
     * @return self Self instance
     */
    public function setUnidadeID($unidade_id)
    {
        $this->unidade_id = $unidade_id;
        return $this;
    }

    /**
     * Nome do valor nutricional
     * @return string nome of Valor nutricional
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Valor nutricional
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Quantidade do valor nutricional com base na porção
     * @return float quantidade of Valor nutricional
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Valor nutricional
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Valor diário em %
     * @return float valor diário of Valor nutricional
     */
    public function getValorDiario()
    {
        return $this->valor_diario;
    }

    /**
     * Set ValorDiario value to new on param
     * @param float $valor_diario Set valor diário for Valor nutricional
     * @return self Self instance
     */
    public function setValorDiario($valor_diario)
    {
        $this->valor_diario = $valor_diario;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $valor_nutricional = parent::toArray($recursive);
        $valor_nutricional['id'] = $this->getID();
        $valor_nutricional['informacaoid'] = $this->getInformacaoID();
        $valor_nutricional['unidadeid'] = $this->getUnidadeID();
        $valor_nutricional['nome'] = $this->getNome();
        $valor_nutricional['quantidade'] = $this->getQuantidade();
        $valor_nutricional['valordiario'] = $this->getValorDiario();
        return $valor_nutricional;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $valor_nutricional Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($valor_nutricional = [])
    {
        if ($valor_nutricional instanceof self) {
            $valor_nutricional = $valor_nutricional->toArray();
        } elseif (!is_array($valor_nutricional)) {
            $valor_nutricional = [];
        }
        parent::fromArray($valor_nutricional);
        if (!isset($valor_nutricional['id'])) {
            $this->setID(null);
        } else {
            $this->setID($valor_nutricional['id']);
        }
        if (!isset($valor_nutricional['informacaoid'])) {
            $this->setInformacaoID(null);
        } else {
            $this->setInformacaoID($valor_nutricional['informacaoid']);
        }
        if (!isset($valor_nutricional['unidadeid'])) {
            $this->setUnidadeID(null);
        } else {
            $this->setUnidadeID($valor_nutricional['unidadeid']);
        }
        if (!isset($valor_nutricional['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($valor_nutricional['nome']);
        }
        if (!isset($valor_nutricional['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($valor_nutricional['quantidade']);
        }
        if (!array_key_exists('valordiario', $valor_nutricional)) {
            $this->setValorDiario(null);
        } else {
            $this->setValorDiario($valor_nutricional['valordiario']);
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
        $valor_nutricional = parent::publish($requester);
        return $valor_nutricional;
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
        $this->setInformacaoID(Filter::number($this->getInformacaoID()));
        $this->setUnidadeID(Filter::number($this->getUnidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setValorDiario(Filter::float($this->getValorDiario(), $localized));
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
     * @return array All field of ValorNutricional in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getInformacaoID())) {
            $errors['informacaoid'] = _t('valor_nutricional.informacao_id_cannot_empty');
        }
        if (is_null($this->getUnidadeID())) {
            $errors['unidadeid'] = _t('valor_nutricional.unidade_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('valor_nutricional.nome_cannot_empty');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('valor_nutricional.quantidade_cannot_empty');
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
        if (contains(['InformacaoID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'informacaoid' => _t(
                    'valor_nutricional.informacao_id_used',
                    $this->getInformacaoID()
                ),
                'nome' => _t(
                    'valor_nutricional.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, InformacaoID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByInformacaoIDNome()
    {
        return $this->load([
            'informacaoid' => intval($this->getInformacaoID()),
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Informe a que tabela nutricional este valor pertence
     * @return \MZ\Product\Informacao The object fetched from database
     */
    public function findInformacaoID()
    {
        return \MZ\Product\Informacao::findByID($this->getInformacaoID());
    }

    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     * @return \MZ\Product\Unidade The object fetched from database
     */
    public function findUnidadeID()
    {
        return \MZ\Product\Unidade::findByID($this->getUnidadeID());
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'v.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'v.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Valores_Nutricionais v');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('v.nome ASC');
        $query = $query->orderBy('v.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, InformacaoID, Nome
     * @param int $informacao_id informação to find Valor nutricional
     * @param string $nome nome to find Valor nutricional
     * @return self A filled instance or empty when not found
     */
    public static function findByInformacaoIDNome($informacao_id, $nome)
    {
        $result = new self();
        $result->setInformacaoID($informacao_id);
        $result->setNome($nome);
        return $result->loadByInformacaoIDNome();
    }
}

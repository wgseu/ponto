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
 * Taxas, eventos e serviço cobrado nos pedidos
 */
class Servico extends SyncModel
{
    const DESCONTO_ID = 1;
    const ENTREGA_ID = 2;

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    const TIPO_EVENTO = 'Evento';
    const TIPO_TAXA = 'Taxa';

    /**
     * Identificador do serviço
     */
    private $id;
    /**
     * Nome do serviço, Ex.: Comissão, Entrega, Couvert
     */
    private $nome;
    /**
     * Descrição do serviço, Ex.: Show de fulano
     */
    private $descricao;
    /**
     * Detalhes do serviço, Ex.: Com participação especial de fulano
     */
    private $detalhes;
    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    private $tipo;
    /**
     * Informa se a taxa é obrigatória
     */
    private $obrigatorio;
    /**
     * Data de início do evento
     */
    private $data_inicio;
    /**
     * Data final do evento
     */
    private $data_fim;
    /**
     * Tempo de participação máxima que não será obrigatório adicionar o
     * serviço ao pedido
     */
    private $tempo_limite;
    /**
     * Valor do serviço
     */
    private $valor;
    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     */
    private $individual;
    /**
     * Banner do evento
     */
    private $imagem_url;
    /**
     * Informa se o serviço está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Servico
     * @param array $servico All field and values to fill the instance
     */
    public function __construct($servico = [])
    {
        parent::__construct($servico);
    }

    /**
     * Identificador do serviço
     * @return int id of Serviço
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Serviço
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do serviço, Ex.: Comissão, Entrega, Couvert
     * @return string nome of Serviço
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Serviço
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descrição do serviço, Ex.: Show de fulano
     * @return string descrição of Serviço
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Serviço
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Detalhes do serviço, Ex.: Com participação especial de fulano
     * @return string detalhes of Serviço
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Serviço
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     * @return string tipo of Serviço
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Serviço
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa se a taxa é obrigatória
     * @return string obrigatório of Serviço
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * Informa se a taxa é obrigatória
     * @return boolean Check if o of Obrigatorio is selected or checked
     */
    public function isObrigatorio()
    {
        return $this->obrigatorio == 'Y';
    }

    /**
     * Set Obrigatorio value to new on param
     * @param string $obrigatorio Set obrigatório for Serviço
     * @return self Self instance
     */
    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;
        return $this;
    }

    /**
     * Data de início do evento
     * @return string data de início of Serviço
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    /**
     * Set DataInicio value to new on param
     * @param string $data_inicio Set data de início for Serviço
     * @return self Self instance
     */
    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
        return $this;
    }

    /**
     * Data final do evento
     * @return string data final of Serviço
     */
    public function getDataFim()
    {
        return $this->data_fim;
    }

    /**
     * Set DataFim value to new on param
     * @param string $data_fim Set data final for Serviço
     * @return self Self instance
     */
    public function setDataFim($data_fim)
    {
        $this->data_fim = $data_fim;
        return $this;
    }

    /**
     * Tempo de participação máxima que não será obrigatório adicionar o
     * serviço ao pedido
     * @return int tempo limite of Serviço
     */
    public function getTempoLimite()
    {
        return $this->tempo_limite;
    }

    /**
     * Set TempoLimite value to new on param
     * @param int $tempo_limite Set tempo limite for Serviço
     * @return self Self instance
     */
    public function setTempoLimite($tempo_limite)
    {
        $this->tempo_limite = $tempo_limite;
        return $this;
    }

    /**
     * Valor do serviço
     * @return string valor of Serviço
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Serviço
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     * @return string individual of Serviço
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     * @return boolean Check if o of Individual is selected or checked
     */
    public function isIndividual()
    {
        return $this->individual == 'Y';
    }

    /**
     * Set Individual value to new on param
     * @param string $individual Set individual for Serviço
     * @return self Self instance
     */
    public function setIndividual($individual)
    {
        $this->individual = $individual;
        return $this;
    }

    /**
     * Banner do evento
     * @return string imagem of Serviço
     */
    public function getImagemURL()
    {
        return $this->imagem_url;
    }

    /**
     * Set ImagemURL value to new on param
     * @param string $imagem_url Set imagem for Serviço
     * @return self Self instance
     */
    public function setImagemURL($imagem_url)
    {
        $this->imagem_url = $imagem_url;
        return $this;
    }

    /**
     * Informa se o serviço está ativo
     * @return string ativo of Serviço
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o serviço está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param string $ativo Set ativo for Serviço
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $servico = parent::toArray($recursive);
        $servico['id'] = $this->getID();
        $servico['nome'] = $this->getNome();
        $servico['descricao'] = $this->getDescricao();
        $servico['detalhes'] = $this->getDetalhes();
        $servico['tipo'] = $this->getTipo();
        $servico['obrigatorio'] = $this->getObrigatorio();
        $servico['datainicio'] = $this->getDataInicio();
        $servico['datafim'] = $this->getDataFim();
        $servico['tempolimite'] = $this->getTempoLimite();
        $servico['valor'] = $this->getValor();
        $servico['individual'] = $this->getIndividual();
        $servico['imagemurl'] = $this->getImagemURL();
        $servico['ativo'] = $this->getAtivo();
        return $servico;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $servico Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($servico = [])
    {
        if ($servico instanceof self) {
            $servico = $servico->toArray();
        } elseif (!is_array($servico)) {
            $servico = [];
        }
        parent::fromArray($servico);
        if (!isset($servico['id'])) {
            $this->setID(null);
        } else {
            $this->setID($servico['id']);
        }
        if (!isset($servico['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($servico['nome']);
        }
        if (!isset($servico['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($servico['descricao']);
        }
        if (!array_key_exists('detalhes', $servico)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($servico['detalhes']);
        }
        if (!isset($servico['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($servico['tipo']);
        }
        if (!isset($servico['obrigatorio'])) {
            $this->setObrigatorio('N');
        } else {
            $this->setObrigatorio($servico['obrigatorio']);
        }
        if (!array_key_exists('datainicio', $servico)) {
            $this->setDataInicio(null);
        } else {
            $this->setDataInicio($servico['datainicio']);
        }
        if (!array_key_exists('datafim', $servico)) {
            $this->setDataFim(null);
        } else {
            $this->setDataFim($servico['datafim']);
        }
        if (!array_key_exists('tempolimite', $servico)) {
            $this->setTempoLimite(null);
        } else {
            $this->setTempoLimite($servico['tempolimite']);
        }
        if (!isset($servico['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($servico['valor']);
        }
        if (!isset($servico['individual'])) {
            $this->setIndividual('N');
        } else {
            $this->setIndividual($servico['individual']);
        }
        if (!array_key_exists('imagemurl', $servico)) {
            $this->setImagemURL(null);
        } else {
            $this->setImagemURL($servico['imagemurl']);
        }
        if (!isset($servico['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($servico['ativo']);
        }
        return $this;
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for serviço imagem
     */
    public function makeImagemURL($default = false, $default_name = 'servico.png')
    {
        $imagem_url = $this->getImagemURL();
        if ($default) {
            $imagem_url = null;
        }
        return get_image_url($imagem_url, 'servico', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $servico = parent::publish($requester);
        $servico['imagemurl'] = $this->makeImagemURL(false, null);
        return $servico;
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
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataInicio(Filter::datetime($this->getDataInicio()));
        $this->setDataFim(Filter::datetime($this->getDataFim()));
        $this->setTempoLimite(Filter::number($this->getTempoLimite()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $imagem_url = upload_image('raw_imagemurl', 'servico', null, 620, 400, true, 'crop');
        if (is_null($imagem_url) && trim($this->getImagemURL()) != '') {
            $this->setImagemURL($original->getImagemURL());
        } else {
            $this->setImagemURL($imagem_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemURL()) && $dependency->getImagemURL() != $this->getImagemURL()) {
            @unlink(get_image_path($this->getImagemURL(), 'servico'));
        }
        $this->setImagemURL($dependency->getImagemURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Servico in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('servico.nome_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('servico.descricao_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('servico.tipo_invalid');
        }
        if (!Validator::checkBoolean($this->getObrigatorio())) {
            $errors['obrigatorio'] = _t('servico.obrigatorio_invalid');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('servico.valor_cannot_empty');
        } elseif ($this->getValor() < 0) {
            $errors['valor'] = _t('servico.valor_cannot_negative');
        } elseif (is_equal($this->getValor(), 0)) {
            $errors['valor'] = _t('servico.valor_cannot_zero');
        }
        if (!Validator::checkBoolean($this->getIndividual())) {
            $errors['individual'] = _t('servico.individual_invalid');
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('servico.ativo_invalid');
        }
        if ($this->getTipo() == self::TIPO_EVENTO) {
            if (is_null($this->getDataInicio())) {
                $errors['datainicio'] = _t('servico.datainicio_invalid');
            }
            if (is_null($this->getDataFim())) {
                $errors['datafim'] = _t('servico.datafim_invalid');
            }
        } else {
            if (!is_null($this->getDataInicio())) {
                $errors['datainicio'] = _t('servico.datainicio_must_empty');
            }
            if (!is_null($this->getDataFim())) {
                $errors['datafim'] = _t('servico.datafim_must_empty');
            }
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    private function checkAccess()
    {
        if ($this->getID() >= self::DESCONTO_ID && $this->getID() <= self::ENTREGA_ID) {
            throw new \Exception(_t('servico.internal_readonly'));
        }
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        $this->checkAccess();
        return parent::delete();
    }

    /**
     * Gets textual and translated Tipo for Servico
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_EVENTO => _t('servico.tipo_evento'),
            self::TIPO_TAXA => _t('servico.tipo_taxa'),
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
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '(s.nome LIKE ? OR s.descricao LIKE ? OR s.detalhes LIKE ?)';
            $condition[$field] = ['%'.$search.'%', '%'.$search.'%', '%'.$search.'%'];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Servicos s');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }
}

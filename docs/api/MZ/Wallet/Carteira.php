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

namespace MZ\Wallet;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Payment\Pagamento;

/**
 * Informa uma conta bancária ou uma carteira financeira
 */
class Carteira extends SyncModel
{

    /**
     * Tipo de carteira, 'Bancaria' para conta bancária, 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos, 'Credito' para
     * cartão de crédito e 'Local' para caixas e cofres locais
     */
    const TIPO_BANCARIA = 'Bancaria';
    const TIPO_FINANCEIRA = 'Financeira';
    const TIPO_CREDITO = 'Credito';
    const TIPO_LOCAL = 'Local';

    /**
     * Ambiente de execução da API usando o token
     */
    const AMBIENTE_TESTE = 'Teste';
    const AMBIENTE_PRODUCAO = 'Producao';

    /**
     * Código local da carteira
     */
    private $id;
    /**
     * Tipo de carteira, 'Bancaria' para conta bancária, 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos, 'Credito' para
     * cartão de crédito e 'Local' para caixas e cofres locais
     */
    private $tipo;
    /**
     * Informa a carteira superior, exemplo: Banco e cartões como subcarteira
     */
    private $carteira_id;
    /**
     * Código local do banco quando a carteira for bancária
     */
    private $banco_id;
    /**
     * Descrição da carteira, nome dado a carteira cadastrada
     */
    private $descricao;
    /**
     * Número da conta bancária ou usuário da conta de acesso da carteira
     */
    private $conta;
    /**
     * Número da agência da conta bancária ou site da carteira financeira
     */
    private $agencia;
    /**
     * Valor cobrado pela operadora de pagamento para cada transação
     */
    private $transacao;
    /**
     * Limite de crédito
     */
    private $limite;
    /**
     * Token para integração de pagamentos
     */
    private $token;
    /**
     * Ambiente de execução da API usando o token
     */
    private $ambiente;
    /**
     * Logo do gateway de pagamento
     */
    private $logo_url;
    /**
     * Cor predominante da marca da instituição
     */
    private $cor;
    /**
     * Informa se a carteira ou conta bancária está ativa
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Carteira
     * @param array $carteira All field and values to fill the instance
     */
    public function __construct($carteira = [])
    {
        parent::__construct($carteira);
    }

    /**
     * Código local da carteira
     * @return int id of Carteira
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Carteira
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Tipo de carteira, 'Bancaria' para conta bancária, 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos, 'Credito' para
     * cartão de crédito e 'Local' para caixas e cofres locais
     * @return string tipo of Carteira
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Carteira
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa a carteira superior, exemplo: Banco e cartões como subcarteira
     * @return int carteira superior of Carteira
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira superior for Carteira
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Código local do banco quando a carteira for bancária
     * @return int banco of Carteira
     */
    public function getBancoID()
    {
        return $this->banco_id;
    }

    /**
     * Set BancoID value to new on param
     * @param int $banco_id Set banco for Carteira
     * @return self Self instance
     */
    public function setBancoID($banco_id)
    {
        $this->banco_id = $banco_id;
        return $this;
    }

    /**
     * Descrição da carteira, nome dado a carteira cadastrada
     * @return string descrição of Carteira
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Carteira
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Número da conta bancária ou usuário da conta de acesso da carteira
     * @return string conta of Carteira
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set Conta value to new on param
     * @param string $conta Set conta for Carteira
     * @return self Self instance
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * Número da agência da conta bancária ou site da carteira financeira
     * @return string agência of Carteira
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set Agencia value to new on param
     * @param string $agencia Set agência for Carteira
     * @return self Self instance
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * Valor cobrado pela operadora de pagamento para cada transação
     * @return string transação of Carteira
     */
    public function getTransacao()
    {
        return $this->transacao;
    }

    /**
     * Set Transacao value to new on param
     * @param string $transacao Set transação for Carteira
     * @return self Self instance
     */
    public function setTransacao($transacao)
    {
        $this->transacao = $transacao;
        return $this;
    }

    /**
     * Limite de crédito
     * @return string limite de crédito of Carteira
     */
    public function getLimite()
    {
        return $this->limite;
    }

    /**
     * Set Limite value to new on param
     * @param string $limite Set limite de crédito for Carteira
     * @return self Self instance
     */
    public function setLimite($limite)
    {
        $this->limite = $limite;
        return $this;
    }

    /**
     * Token para integração de pagamentos
     * @return string token of Carteira
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token value to new on param
     * @param string $token Set token for Carteira
     * @return self Self instance
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Ambiente de execução da API usando o token
     * @return string ambiente of Carteira
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set Ambiente value to new on param
     * @param string $ambiente Set ambiente for Carteira
     * @return self Self instance
     */
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
        return $this;
    }

    /**
     * Logo do gateway de pagamento
     * @return string logo of Carteira
     */
    public function getLogoURL()
    {
        return $this->logo_url;
    }

    /**
     * Set LogoURL value to new on param
     * @param string $logo_url Set logo for Carteira
     * @return self Self instance
     */
    public function setLogoURL($logo_url)
    {
        $this->logo_url = $logo_url;
        return $this;
    }

    /**
     * Cor predominante da marca da instituição
     * @return string cor of Carteira
     */
    public function getCor()
    {
        return $this->cor;
    }

    /**
     * Set Cor value to new on param
     * @param string $cor Set cor for Carteira
     * @return self Self instance
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
        return $this;
    }

    /**
     * Informa se a carteira ou conta bancária está ativa
     * @return string ativa of Carteira
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a carteira ou conta bancária está ativa
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param string $ativa Set ativa for Carteira
     * @return self Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $carteira = parent::toArray($recursive);
        $carteira['id'] = $this->getID();
        $carteira['tipo'] = $this->getTipo();
        $carteira['carteiraid'] = $this->getCarteiraID();
        $carteira['bancoid'] = $this->getBancoID();
        $carteira['descricao'] = $this->getDescricao();
        $carteira['conta'] = $this->getConta();
        $carteira['agencia'] = $this->getAgencia();
        $carteira['transacao'] = $this->getTransacao();
        $carteira['limite'] = $this->getLimite();
        $carteira['token'] = $this->getToken();
        $carteira['ambiente'] = $this->getAmbiente();
        $carteira['logourl'] = $this->getLogoURL();
        $carteira['cor'] = $this->getCor();
        $carteira['ativa'] = $this->getAtiva();
        return $carteira;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $carteira Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($carteira = [])
    {
        if ($carteira instanceof self) {
            $carteira = $carteira->toArray();
        } elseif (!is_array($carteira)) {
            $carteira = [];
        }
        parent::fromArray($carteira);
        if (!isset($carteira['id'])) {
            $this->setID(null);
        } else {
            $this->setID($carteira['id']);
        }
        if (!isset($carteira['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($carteira['tipo']);
        }
        if (!array_key_exists('carteiraid', $carteira)) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($carteira['carteiraid']);
        }
        if (!array_key_exists('bancoid', $carteira)) {
            $this->setBancoID(null);
        } else {
            $this->setBancoID($carteira['bancoid']);
        }
        if (!isset($carteira['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($carteira['descricao']);
        }
        if (!array_key_exists('conta', $carteira)) {
            $this->setConta(null);
        } else {
            $this->setConta($carteira['conta']);
        }
        if (!array_key_exists('agencia', $carteira)) {
            $this->setAgencia(null);
        } else {
            $this->setAgencia($carteira['agencia']);
        }
        if (!isset($carteira['transacao'])) {
            $this->setTransacao(0);
        } else {
            $this->setTransacao($carteira['transacao']);
        }
        if (!array_key_exists('limite', $carteira)) {
            $this->setLimite(null);
        } else {
            $this->setLimite($carteira['limite']);
        }
        if (!array_key_exists('token', $carteira)) {
            $this->setToken(null);
        } else {
            $this->setToken($carteira['token']);
        }
        if (!array_key_exists('ambiente', $carteira)) {
            $this->setAmbiente(null);
        } else {
            $this->setAmbiente($carteira['ambiente']);
        }
        if (!array_key_exists('logourl', $carteira)) {
            $this->setLogoURL(null);
        } else {
            $this->setLogoURL($carteira['logourl']);
        }
        if (!array_key_exists('cor', $carteira)) {
            $this->setCor(null);
        } else {
            $this->setCor($carteira['cor']);
        }
        if (!isset($carteira['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($carteira['ativa']);
        }
        return $this;
    }

    public function fetchAvailable()
    {
        return (float)Pagamento::sum(['valor'], [
            'estado' => Pagamento::ESTADO_PAGO,
            'ate_datacompensacao' => DB::now(),
            'carteiraid' => $this->getID(),
            'agrupamentoid' => null,
        ]);
    }

    public function fetchToReceive()
    {
        return (float)Pagamento::sum(['valor'], [
            'estado' => Pagamento::ESTADO_PAGO,
            'apartir_datacompensacao' => DB::now(),
            'carteiraid' => $this->getID(),
            'agrupamentoid' => null,
        ]);
    }

    /**
     * Get relative logo path or default logo
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for carteira logo
     */
    public function makeLogoURL($default = false, $default_name = 'carteira.png')
    {
        $logo_url = $this->getLogoURL();
        if ($default) {
            $logo_url = null;
        }
        return get_image_url($logo_url, 'carteira', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $carteira = parent::publish($requester);
        $carteira['logourl'] = $this->makeLogoURL(false, null);
        return $carteira;
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
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setBancoID(Filter::number($this->getBancoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setConta(Filter::string($this->getConta()));
        $this->setAgencia(Filter::string($this->getAgencia()));
        $this->setTransacao(Filter::money($this->getTransacao(), $localized));
        $this->setLimite(Filter::money($this->getLimite(), $localized));
        $this->setToken(Filter::text($this->getToken()));
        $this->setAmbiente(Filter::string($this->getAmbiente()));
        $logo_url = upload_image('raw_logourl', 'carteira', null, 400, 240); // 5:3
        if (is_null($logo_url) && trim($this->getLogoURL()) != '') {
            $this->setLogoURL($original->getLogoURL());
        } else {
            $this->setLogoURL($logo_url);
        }
        $this->setCor(Filter::string($this->getCor()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getLogoURL()) && $dependency->getLogoURL() != $this->getLogoURL()) {
            @unlink(get_image_path($this->getLogoURL(), 'carteira'));
        }
        $this->setLogoURL($dependency->getLogoURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Carteira in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!is_null($this->getCarteiraID())) {
            $carteirapai = $this->findCarteiraID();
            if (!$carteirapai->exists()) {
                $errors['carteiraid'] = _t('carteira.carteirapai_not_found');
            } elseif (!is_null($carteirapai->getCarteiraID())) {
                $errors['carteiraid'] = _t('carteira.carteirapai_already');
            } elseif ($carteirapai->getID() == $this->getID()) {
                $errors['carteiraid'] = _t('carteira.carteirapai_same');
            }
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('carteira.tipo_invalid');
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getBancoID())) {
            $errors['bancoid'] = 'O banco não foi informado';
        }
        if ($this->getTipo() == self::TIPO_FINANCEIRA && !is_null($this->getBancoID())) {
            $errors['bancoid'] = 'O banco não pode ser informado';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('carteira.descricao_cannot_empty');
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getAgencia())) {
            $errors['agencia'] = 'A agência não pode ser vazia';
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getConta())) {
            $errors['conta'] = 'A conta não pode ser vazia';
        }
        if (is_null($this->getTransacao())) {
            $errors['transacao'] = _t('carteira.transacao_cannot_empty');
        }
        if (!Validator::checkInSet($this->getAmbiente(), self::getAmbienteOptions(), true)) {
            $errors['ambiente'] = _t('carteira.ambiente_invalid');
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = _t('carteira.ativa_invalid');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Informa a carteira superior, exemplo: Banco e cartões como subcarteira
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
    }

    /**
     * Código local do banco quando a carteira for bancária
     * @return \MZ\Wallet\Banco The object fetched from database
     */
    public function findBancoID()
    {
        return \MZ\Wallet\Banco::findByID($this->getBancoID());
    }

    /**
     * Gets textual and translated Tipo for Carteira
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_BANCARIA => _t('carteira.tipo_bancaria'),
            self::TIPO_FINANCEIRA => _t('carteira.tipo_financeira'),
            self::TIPO_CREDITO => _t('carteira.tipo_credito'),
            self::TIPO_LOCAL => _t('carteira.tipo_local'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Ambiente for Carteira
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getAmbienteOptions($index = null)
    {
        $options = [
            self::AMBIENTE_TESTE => _t('carteira.ambiente_teste'),
            self::AMBIENTE_PRODUCAO => _t('carteira.ambiente_producao'),
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
            $search = trim($condition['search']);
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Carteiras c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Sum all values available from Carteira
     * @param array  $condition Condition to select wallet to sum
     * @return float Available value
     */
    public static function sumAvailable($condition = [])
    {
        $instance = new self();
        $query = $instance->query($condition);
        $query = $query->leftJoin('Pagamentos p ON p.carteiraid = c.id' .
            ' AND p.estado = ?' .
            ' AND p.datacompensacao <= ?' .
            ' AND p.agrupamentoid IS NULL',
            Pagamento::ESTADO_PAGO,
            DB::now()
        );
        $query = $query->select(null)->select('SUM(p.valor)')->orderBy(null);
        return (float)$query->fetchColumn();
    }

    /**
     * Sum all values to receive from Carteira
     * @param array  $condition Condition to select wallet to sum
     * @return float To receive value
     */
    public static function sumToReceive($condition = [])
    {
        $instance = new self();
        $query = $instance->query($condition);
        $query = $query->leftJoin('Pagamentos p ON p.carteiraid = c.id' .
            ' AND p.estado = ?' .
            ' AND p.datacompensacao <= ?' .
            ' AND p.agrupamentoid IS NULL',
            Pagamento::ESTADO_PAGO,
            DB::now()
        );
        $query = $query->select(null)->select('SUM(p.valor)')->orderBy(null);
        return (float)$query->fetchColumn();
    }
}

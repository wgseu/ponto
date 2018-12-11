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
namespace MZ\Company;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends SyncModel
{

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    const MODO_FUNCIONAMENTO = 'Funcionamento';
    const MODO_OPERACAO = 'Operacao';
    const MODO_ENTREGA = 'Entrega';

    /**
     * Identificador do horário
     */
    private $id;
    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    private $modo;
    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     */
    private $funcao_id;
    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     */
    private $prestador_id;
    /**
     * Permite informar o horário de atendimento para cada integração
     */
    private $integracao_id;
    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     */
    private $inicio;
    /**
     * Horário final de funcionamento do estabelecimento contando em minutos a
     * partir de domingo
     */
    private $fim;
    /**
     * Mensagem que será mostrada quando o estabelecimento estiver fechado por
     * algum motivo
     */
    private $mensagem;
    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     */
    private $fechado;

    /**
     * Constructor for a new empty instance of Horario
     * @param array $horario All field and values to fill the instance
     */
    public function __construct($horario = [])
    {
        parent::__construct($horario);
    }

    /**
     * Identificador do horário
     * @return int id of Horário
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador do horário
     * @param int $id Set id for Horário
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     * @return string modo of Horário
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     * @param string $modo Set modo for Horário
     * @return self Self instance
     */
    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     * @return int função of Horário
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     * @param int $funcao_id Set função for Horário
     * @return self Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     * @return int prestador of Horário
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     * @param int $prestador_id Set prestador for Horário
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Permite informar o horário de atendimento para cada integração
     * @return int integração of Horário
     */
    public function getIntegracaoID()
    {
        return $this->integracao_id;
    }

    /**
     * Permite informar o horário de atendimento para cada integração
     * @param int $integracao_id Set integração for Horário
     * @return self Self instance
     */
    public function setIntegracaoID($integracao_id)
    {
        $this->integracao_id = $integracao_id;
        return $this;
    }

    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     * @return int início of Horário
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     * @param int $inicio Set início for Horário
     * @return self Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Horário final de funcionamento do estabelecimento contando em minutos a
     * partir de domingo
     * @return int fim of Horário
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Horário final de funcionamento do estabelecimento contando em minutos a
     * partir de domingo
     * @param int $fim Set fim for Horário
     * @return self Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Mensagem que será mostrada quando o estabelecimento estiver fechado por
     * algum motivo
     * @return string mensagem of Horário
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Mensagem que será mostrada quando o estabelecimento estiver fechado por
     * algum motivo
     * @param string $mensagem Set mensagem for Horário
     * @return self Self instance
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     * @return string fechado of Horário
     */
    public function getFechado()
    {
        return $this->fechado;
    }

    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     * @return boolean Check if o of Fechado is selected or checked
     */
    public function isFechado()
    {
        return $this->fechado == 'Y';
    }

    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     * @param string $fechado Set fechado for Horário
     * @return self Self instance
     */
    public function setFechado($fechado)
    {
        $this->fechado = $fechado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $horario = parent::toArray($recursive);
        $horario['id'] = $this->getID();
        $horario['modo'] = $this->getModo();
        $horario['funcaoid'] = $this->getFuncaoID();
        $horario['prestadorid'] = $this->getPrestadorID();
        $horario['integracaoid'] = $this->getIntegracaoID();
        $horario['inicio'] = $this->getInicio();
        $horario['fim'] = $this->getFim();
        $horario['mensagem'] = $this->getMensagem();
        $horario['fechado'] = $this->getFechado();
        return $horario;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $horario Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($horario = [])
    {
        if ($horario instanceof self) {
            $horario = $horario->toArray();
        } elseif (!is_array($horario)) {
            $horario = [];
        }
        parent::fromArray($horario);
        if (!isset($horario['id'])) {
            $this->setID(null);
        } else {
            $this->setID($horario['id']);
        }
        if (!isset($horario['modo'])) {
            $this->setModo('Funcionamento');
        } else {
            $this->setModo($horario['modo']);
        }
        if (!array_key_exists('funcaoid', $horario)) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($horario['funcaoid']);
        }
        if (!array_key_exists('prestadorid', $horario)) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($horario['prestadorid']);
        }
        if (!array_key_exists('integracaoid', $horario)) {
            $this->setIntegracaoID(null);
        } else {
            $this->setIntegracaoID($horario['integracaoid']);
        }
        if (!isset($horario['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($horario['inicio']);
        }
        if (!isset($horario['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($horario['fim']);
        }
        if (!array_key_exists('mensagem', $horario)) {
            $this->setMensagem(null);
        } else {
            $this->setMensagem($horario['mensagem']);
        }
        if (!isset($horario['fechado'])) {
            $this->setFechado('N');
        } else {
            $this->setFechado($horario['fechado']);
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
        $horario = parent::publish($requester);
        return $horario;
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
        $this->setModo(Filter::string($this->getModo()));
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setIntegracaoID(Filter::number($this->getIntegracaoID()));
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setMensagem(Filter::string($this->getMensagem()));
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
     * @return array All field of Horario in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getModo(), self::getModoOptions())) {
            $errors['modo'] = _t('horario.modo_invalid');
        }
        if (is_null($this->getInicio())) {
            $errors['inicio'] = _t('horario.inicio_cannot_empty');
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = _t('horario.fim_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getFechado())) {
            $errors['fechado'] = _t('horario.fechado_invalid');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     * @return \MZ\Provider\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        if (is_null($this->getFuncaoID())) {
            return new \MZ\Provider\Funcao();
        }
        return \MZ\Provider\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        if (is_null($this->getPrestadorID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Permite informar o horário de atendimento para cada integração
     * @return \MZ\System\Integracao The object fetched from database
     */
    public function findIntegracaoID()
    {
        if (is_null($this->getIntegracaoID())) {
            return new \MZ\System\Integracao();
        }
        return \MZ\System\Integracao::findByID($this->getIntegracaoID());
    }

    /**
     * Gets textual and translated Modo for Horario
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getModoOptions($index = null)
    {
        $options = [
            self::MODO_FUNCIONAMENTO => _t('horario.modo_funcionamento'),
            self::MODO_OPERACAO => _t('horario.modo_operacao'),
            self::MODO_ENTREGA => _t('horario.modo_entrega'),
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
            $field = 'h.mensagem LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'h.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Horarios h');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('h.id ASC');
        return DB::buildCondition($query, $condition);
    }
}

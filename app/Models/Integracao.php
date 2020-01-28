<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
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
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace App\Models;

use App\Concerns\ModelEvents;
use App\Core\Settings;
use App\Interfaces\ValidateInterface;
use App\Util\Filter;
use Illuminate\Database\Eloquent\Model;

/**
 * Informa quais integrações estão disponíveis
 */
class Integracao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de integração se pedido, login, dispositivo, pagamento, outros
     */
    public const TIPO_PEDIDO = 'pedido';
    public const TIPO_LOGIN = 'login';
    public const TIPO_DISPOSITIVO = 'dispositivo';
    public const TIPO_PAGAMENTO = 'pagamento';
    public const TIPO_OUTROS = 'outros';

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'integracoes';

    /**
     * Setting model
     *
     * @var Settings
     */
    public $options;

    /**
     * Setting model
     *
     * @var Settings
     */
    public $associations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'tipo',
        'login',
        'secret',
        'associacoes',
        'ativo',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ativo' => false,
    ];

    public function __construct(array $attributes = [])
    {
        $this->options = new Settings();
        $this->associations = new Settings();
        parent::__construct($attributes);
    }

    /**
     * Retorna as opções da integração como string json
     *
     * @return string
     */
    public function getOpcoesAttribute()
    {
        $this->options->includeDefaults = app('settings')->includeDefaults;
        $this->loadOptions();
        return json_encode(Filter::emptyObject($this->options->getValues()));
    }

    public function setOpcoesAttribute($value)
    {
        $this->options->addValues(json_decode($value ?? '{}', true));
        $this->attributes['opcoes'] = base64_encode(json_encode($this->options->getValues(false)));
    }

    /**
     * Carrega as opções da integração
     *
     * @return void
     */
    public function loadOptions()
    {
        $this->options->addValues(
            json_decode(base64_decode($this->getAttributeFromArray('opcoes')), true)
        );
    }

    /**
     * Retorna as associações da integração como string json
     *
     * @return string
     */
    public function getAssociacoesAttribute()
    {
        $this->associations->includeDefaults = app('settings')->includeDefaults;
        $this->loadAssociations();
        return json_encode(Filter::emptyObject($this->associations->getValues()));
    }

    public function setAssociacoesAttribute($value)
    {
        $this->associations->addValues(json_decode($value ?? '{}', true));
        $this->attributes['associacoes'] = base64_encode(json_encode($this->associations->getValues(false)));
    }

    /**
     * Carrega as associações da integração
     *
     * @return void
     */
    public function loadAssociations()
    {
        $this->associations->addValues(
            json_decode(base64_decode($this->getAttributeFromArray('associacoes')), true)
        );
    }

    /**
     * Regras:
     * Se a Intregração estiver ativa Login e senha não podem ser nulos
     */
    public function validate($old)
    {
        $errors = [];
        if (
            $this->ativo &&
            (
                is_null($this->login) ||
                is_null($this->secret)
            )
        ) {
            $errors['ativo'] = __('messages.integration_active_login_cannot_null');
        }
        return $errors;
    }
}

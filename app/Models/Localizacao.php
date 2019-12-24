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
use App\Interfaces\ValidateInterface;
use App\Util\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Endereço detalhado de um cliente
 */
class Localizacao extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Tipo de endereço Casa ou Apartamento
     */
    public const TIPO_CASA = 'casa';
    public const TIPO_APARTAMENTO = 'apartamento';
    public const TIPO_CONDOMINIO = 'condominio';

    public const DELETED_AT = 'data_arquivado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'localizacoes';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id',
        'bairro_id',
        'zona_id',
        'cep',
        'logradouro',
        'numero',
        'tipo',
        'complemento',
        'condominio',
        'bloco',
        'apartamento',
        'referencia',
        'latitude',
        'longitude',
        'apelido',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_CASA,
    ];

    /**
     * Cliente a qual esse endereço pertence
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Bairro do endereço
     */
    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'bairro_id');
    }

    /**
     * Informa a zona do bairro
     */
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    /**
     * Regras:
     * Se o tipo de Localização for apartamento o atributo apartamento é obrigatório;
     * Se o tipo de Localização for condominio o atributo condominio é obrigatório;
     */
    public function validate($old)
    {
        $errors = [];
        if (!Validator::checkCEP($this->cep, true)) {
            $errors['cep'] = __('messages.cep_invalid');
        }
        if ($this->tipo == Localizacao::TIPO_APARTAMENTO && is_null($this->apartamento)) {
            $errors['apartamento'] = __('messages.localizacao_tipo_required_apartamento');
        }
        if ($this->tipo == Localizacao::TIPO_CONDOMINIO && is_null($this->condominio)) {
            $errors['condominio'] = __('messages.localizacao_tipo_required_condominio');
        }
        return $errors;
    }
}

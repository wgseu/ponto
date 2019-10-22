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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Moedas financeiras de um país
 */
class Moeda extends Model implements ValidateInterface
{
    use ModelEvents;

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'moedas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'simbolo',
        'codigo',
        'divisao',
        'fracao',
        'formato',
        'conversao',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ativa' => false,
    ];

        /**
     * Regras:
     * Se a moeda estiver ativa a conversao não pode ser nula,
     * É obrigatório a presença de {value} em formato e não deve ser o unica caracteristica exemple R$ {value}
     * É obrigatório conter um dos numeros da lista na divisao para definir a quantidade de casas decimais.
     */
    public function validate()
    {
        $errors = [];
        $value = strpos($this->formato, '{value}');
        $lista = [1,10,100,1000,10000];
        if ($this->ativa == true) {
            if (is_null($this->conversao)) {
                $errors['conversao'] = __('messages.moeda_active_null_conversion');
            }
        }
        if ($value == false || strlen($this->formato) <= 8) {
            $errors['formato'] = __('messages.moeda_invalid_format');
        }
        if (in_array($this->divisao, $lista) === false) {
            $errors['divisao'] = __('messages.moeda_invalid_divisao');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}

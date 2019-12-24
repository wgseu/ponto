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
use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Prestador de serviço que realiza alguma tarefa na empresa
 */
class Prestador extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     */
    public const VINCULO_FUNCIONARIO = 'funcionario';
    public const VINCULO_PRESTADOR = 'prestador';
    public const VINCULO_AUTONOMO = 'autonomo';

    public const CREATED_AT = 'data_cadastro';
    public const DELETED_AT = 'data_termino';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prestadores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'pin',
        'funcao_id',
        'cliente_id',
        'empresa_id',
        'vinculo',
        'porcentagem',
        'pontuacao',
        'remuneracao',
        'data_termino',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'vinculo' => self::VINCULO_FUNCIONARIO,
        'porcentagem' => 0,
        'pontuacao' => 0,
        'remuneracao' => 0,
    ];

    /**
     * Função do prestada na empresa
     */
    public function funcao()
    {
        return $this->belongsTo(Funcao::class, 'funcao_id');
    }

    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     */
    public function empresa()
    {
        return $this->belongsTo(Prestador::class, 'empresa_id');
    }

    public function validate($old)
    {
        $errors = [];
        $cliente = $this->cliente;
        if (trim($cliente->login) == '') {
            $errors['cliente_id'] = __('messages.user_not_login');
        } elseif ($cliente->tipo != Cliente::TIPO_FISICA) {
            $errors['cliente_id'] = __('messages.user_not_cpf');
        } elseif (is_null($cliente->senha)) {
            $errors['cliente_id'] = __('messages.user_not_password');
        }
        if ($this->pontuacao < 0) {
            $errors['pontuacao'] = __('messages.score_negative');
        }
        if ($this->porcentagem < 0) {
            $errors['porcentagem'] = __('messages.commission_negative');
        }
        if ($this->remuneracao < 0) {
            $errors['remuneracao'] = __('messages.remuneration_negative');
        }
        return $errors;
    }
}

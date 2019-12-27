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

use App\Util\Image;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Informa uma conta bancária ou uma carteira financeira
 */
class Carteira extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Tipo de carteira, Bancaria: para conta bancária, Financeira: para
     * carteira financeira da empresa ou de sites de pagamentos, Credito: para
     * cartão de crédito e Local: para caixas e cofres locais
     */
    public const TIPO_BANCARIA = 'bancaria';
    public const TIPO_FINANCEIRA = 'financeira';
    public const TIPO_CREDITO = 'credito';
    public const TIPO_LOCAL = 'local';

    /**
     * Ambiente de execução da API usando o token
     */
    public const AMBIENTE_TESTE = 'teste';
    public const AMBIENTE_PRODUCAO = 'producao';

    public const DELETED_AT = 'data_desativada';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carteiras';

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
        'tipo',
        'carteira_id',
        'banco_id',
        'descricao',
        'conta',
        'agencia',
        'transacao',
        'limite',
        'token',
        'ambiente',
        'logo_url',
        'logo',
        'cor',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'transacao' => 0,
        'ativa' => true,
    ];

    /**
     * Informa a carteira superior, exemplo: Banco e cartões como subcarteira
     */
    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }

    /**
     * Código local do banco quando a carteira for bancária
     */
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getLogoUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setLogoUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->logo_url) ? null : $this->attributes['logo_url'];
        }
        $this->attributes['logo_url'] = $value;
    }

    public function setLogoAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['logo_url'] = Image::upload($value, 'wallets', 128, 128);
        }
    }

    /**
     * Limpa os recursos do cliente atual se alterado
     *
     * @param self $old
     * @return void
     */
    public function clean($old)
    {
        if (!is_null($this->logo_url) && $this->logo_url != $old->logo_url) {
            Storage::delete($this->attributes['logo_url']);
        }
        $this->attributes['logo_url'] = is_null($old->logo_url) ? null : $old->attributes['logo_url'];
    }

    public function validate($old)
    {
        $errors = [];
        if (!is_null($this->carteira_id)) {
            $carteirapai = $this->carteira;
            if (is_null($carteirapai)) {
                $errors['carteira_id'] = __('messages.carteirapai_not_found');
            } elseif (!is_null($carteirapai->carteira_id)) {
                $errors['carteira_id'] = __('messages.carteirapai_already');
            } elseif ($carteirapai->id == $this->id) {
                $errors['carteira_id'] = __('messages.carteirapai_same');
            }
        }
        if ($this->tipo == self::TIPO_BANCARIA && is_null($this->banco_id)) {
            $errors['banco_id'] = __('messages.bank_null');
        }
        if ($this->tipo == self::TIPO_FINANCEIRA && !is_null($this->banco_id)) {
            $errors['banco_id'] = __('messages.bank_not_be_informed');
        }
        if ($this->tipo == self::TIPO_BANCARIA && is_null($this->agencia)) {
            $errors['agencia'] = __('messages.agency_null');
        }
        if ($this->tipo == self::TIPO_BANCARIA && is_null($this->conta)) {
            $errors['conta'] = __('messages.account_null');
        }
        return $errors;
    }
}

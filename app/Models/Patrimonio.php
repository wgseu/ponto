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

/**
 * Informa detalhadamente um bem da empresa
 */
class Patrimonio extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Estado de conservação do bem
     */
    public const ESTADO_NOVO = 'novo';
    public const ESTADO_CONSERVADO = 'conservado';
    public const ESTADO_RUIM = 'ruim';

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patrimonios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empresa_id',
        'fornecedor_id',
        'numero',
        'descricao',
        'quantidade',
        'altura',
        'largura',
        'comprimento',
        'estado',
        'custo',
        'valor',
        'ativo',
        'imagem_url',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'altura' => 0,
        'largura' => 0,
        'comprimento' => 0,
        'estado' => self::ESTADO_NOVO,
        'custo' => 0,
        'valor' => 0,
        'ativo' => true,
    ];

    /**
     * Empresa a que esse bem pertence
     */
    public function empresa()
    {
        return $this->belongsTo(Cliente::class, 'empresa_id');
    }

    /**
     * Fornecedor do bem
     */
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function validate($old)
    {
        $errors = [];
        if ($this->quantidade < 0) {
            $errors['quantidade'] = __('messages.quantidade_cannot_negative');
        }
        if ($this->altura < 0) {
            $errors['altura'] = __('messages.altura_cannot_negative');
        }
        if ($this->largura < 0) {
            $errors['largura'] = __('messages.largura_cannot_negative');
        }
        if ($this->comprimento < 0) {
            $errors['comprimento'] = __('messages.comprimento_cannot_negative');
        }
        if ($this->custo < 0) {
            $errors['custo'] = __('messages.custo_cannot_negative');
        }
        if ($this->valor < 0) {
            $errors['valor'] = __('messages.valor_cannot_negative');
        }
        if (!$this->ativo && !$this->exists) {
            $errors['ativo'] = __('messages.cannot_create_disabled');
        }
        return $errors;
    }
}

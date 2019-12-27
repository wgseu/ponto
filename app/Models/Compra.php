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

use App\Util\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Compras realizadas em uma lista num determinado fornecedor
 */
class Compra extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'compras';

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
        'numero',
        'comprador_id',
        'fornecedor_id',
        'conta_id',
        'documento_url',
        'documento',
        'data_compra',
    ];

    /**
     * Informa o funcionário que comprou os produtos da lista
     */
    public function comprador()
    {
        return $this->belongsTo(Prestador::class, 'comprador_id');
    }

    /**
     * Fornecedor em que os produtos foram compras
     */
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    /**
     * Conta que foi gerada para essa compra
     */
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getDocumentoUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setDocumentoUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->documento_url) ? null : $this->attributes['documento_url'];
        }
        $this->attributes['documento_url'] = $value;
    }

    public function setDocumentoAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['documento_url'] = Upload::send($value, 'docs/purchases', 'private');
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
        if (!is_null($this->documento_url) && $this->documento_url != $old->documento_url) {
            Storage::delete($this->attributes['documento_url']);
        }
        $this->attributes['documento_url'] = is_null($old->documento_url) ? null : $old->attributes['documento_url'];
    }
}

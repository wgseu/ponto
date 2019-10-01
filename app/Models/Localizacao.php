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

use Illuminate\Database\Eloquent\Model;

/**
 * Endereço detalhado de um cliente
 */
class Localizacao extends Model
{
    /**
     * Tipo de endereço Casa ou Apartamento
     */
    const TIPO_CASA = 'casa';
    const TIPO_APARTAMENTO = 'apartamento';
    const TIPO_CONDOMINIO = 'condominio';

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
     * The model's default values for attributes.
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
        'data_arquivado',
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
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Bairro do endereço
     */
    public function bairro()
    {
        return $this->belongsTo('App\Models\Bairro', 'bairro_id');
    }

    /**
     * Informa a zona do bairro
     */
    public function zona()
    {
        return $this->belongsTo('App\Models\Zona', 'zona_id');
    }
}
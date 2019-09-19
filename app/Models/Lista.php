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
 * Lista de compras de produtos
 */
class Lista extends Model
{
    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     */
    const ESTADO_ANALISE = 'analise';
    const ESTADO_FECHADA = 'fechada';
    const ESTADO_COMPRADA = 'comprada';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'listas';

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
    protected $attributes = [
        'estado' => self::ESTADO_ANALISE,
    ];

    /**
     * Informa o funcionário encarregado de fazer as compras
     */
    public function encarregado()
    {
        return $this->belongsTo('App\Models\Prestador', 'encarregado_id');
    }

    /**
     * Informações da viagem para realizar as compras
     */
    public function viagem()
    {
        return $this->belongsTo('App\Models\Viagem', 'viagem_id');
    }
}

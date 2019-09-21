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
 * Permite acesso à uma determinada funcionalidade da lista de permissões
 */
class Acesso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'acessos';

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
        'funcao_id',
        'permissao_id',
    ];

    /**
     * Função a que a permissão se aplica
     */
    public function funcao()
    {
        return $this->belongsTo('App\Models\Funcao', 'funcao_id');
    }

    /**
     * Permissão liberada para a função
     */
    public function permissao()
    {
        return $this->belongsTo('App\Models\Permissao', 'permissao_id');
    }
}

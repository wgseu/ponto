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
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    const MODO_FUNCIONAMENTO = 'funcionamento';
    const MODO_OPERACAO = 'operacao';
    const MODO_ENTREGA = 'entrega';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horarios';

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
        'modo',
        'funcao_id',
        'prestador_id',
        'inicio',
        'fim',
        'mensagem',
        'entrega_minima',
        'entrega_maxima',
        'fechado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'modo' => self::MODO_FUNCIONAMENTO,
        'entrega_maxima' => 0,
        'fechado' => false,
    ];

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     */
    public function funcao()
    {
        return $this->belongsTo('App\Models\Funcao', 'funcao_id');
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }

    public function validate()
    {
    }
}

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
 * Impressora para impressão de serviços e contas
 */
class Impressora extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Modo de impressão
     */
    const MODO_TERMINAL = 'terminal';
    const MODO_CAIXA = 'caixa';
    const MODO_SERVICO = 'servico';
    const MODO_ESTOQUE = 'estoque';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'impressoras';

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
        'dispositivo_id',
        'setor_id',
        'nome',
        'modelo',
        'modo',
        'opcoes',
        'colunas',
        'avanco',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'modo' => self::MODO_TERMINAL,
        'colunas' => 48,
        'avanco' => 6,
    ];

    /**
     * Dispositivo que contém a impressora
     */
    public function dispositivo()
    {
        return $this->belongsTo('App\Models\Dispositivo', 'dispositivo_id');
    }

    /**
     * Setor de impressão
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_id');
    }

    public function validate()
    {
    }
}

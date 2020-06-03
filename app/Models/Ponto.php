<?php

namespace App\Models;

use DateTime;
use App\Concerns\ModelEvents;
use Illuminate\Database\Eloquent\Model;

/**
 * Informações da empresa
 */
class Ponto extends Model
{
    use ModelEvents;

    public $timestamps = false;

    public const TIPO_PONTO = 'ponto';
    public const TIPO_CORRECAO = 'correcao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pontos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'colaborador_id',
        'data_ponto',
        'latitude',
        'longitude',
        'anexo_url',
        'descricao',
        'tipo',
    ];

    /**
     * Informa o colaborador do ponto
     */
    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}

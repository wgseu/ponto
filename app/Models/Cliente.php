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
use App\Util\Validator;
use App\Concerns\ModelEvents;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Interfaces\ValidateInterface;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Interfaces\AuthorizableInterface;
use App\Interfaces\ValidateInsertInterface;

/**
 * Informações de cliente físico ou jurídico. Clientes, empresas,
 * funcionários, fornecedores e parceiros são cadastrados aqui
 */
class Cliente extends User implements
    ValidateInterface,
    JWTSubject,
    AuthorizableInterface,
    ValidateInsertInterface
{
    use Notifiable;
    use ModelEvents;

    /**
     * Informa o tipo de pessoa, que pode ser física ou jurídica
     */
    public const TIPO_FISICA = 'fisica';
    public const TIPO_JURIDICA = 'juridica';

    /**
     * Informa o gênero do cliente do tipo pessoa física
     */
    public const GENERO_MASCULINO = 'masculino';
    public const GENERO_FEMININO = 'feminino';

    /**
     * Informa o estado da conta do cliente
     */
    public const STATUS_INATIVO = 'inativo';
    public const STATUS_ATIVO = 'ativo';
    public const STATUS_BLOQUEADO = 'bloqueado';

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = 'data_cadastro';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo',
        'fornecedor',
        'empresa_id',
        'login',
        'senha',
        'nome',
        'sobrenome',
        'genero',
        'cpf',
        'rg',
        'im',
        'email',
        'data_nascimento',
        'slogan',
        'limite_compra',
        'instagram',
        'facebook_url',
        'imagem_url',
        'imagem',
        'linguagem',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'senha', 'ip', 'data_envio',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_FISICA,
        'fornecedor' => false,
        'status' => self::STATUS_INATIVO,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'iss' => null,
            'uid' => null,
            'typ' => 'access',
        ];
    }

    /**
     * Create refresh token for customer
     *
     * @return string
     */
    public function getRefreshTokenAttribute()
    {
        return $this->createRefreshToken();
    }

    /**
     * Return if customer is owner
     *
     * @return bool
     */
    public function getProprietarioAttribute()
    {
        return $this->isOwner();
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getImagemUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setImagemUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->imagem_url) ? null : $this->attributes['imagem_url'];
        }
        $this->attributes['imagem_url'] = $value;
    }

    public function setImagemAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['imagem_url'] = Image::upload($value, 'users');
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
        if (!is_null($this->imagem_url) && $this->imagem_url != $old->imagem_url) {
            Storage::delete($this->attributes['imagem_url']);
        }
        $this->attributes['imagem_url'] = is_null($old->imagem_url) ? null : $old->attributes['imagem_url'];
    }

    /**
     * Sempre que alterar a senha, roda a encriptação
     *
     * @param string $senha
     * @return void
     */
    public function setSenhaAttribute($senha)
    {
        if (!empty($senha)) {
            $this->attributes['senha'] = bcrypt($senha);
        }
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function createValidateToken()
    {
        $customClaims = [
            'iss' => null,
            'uid' => $this->id,
            'sub' => null,
            'typ' => 'check',
            'exp' => Carbon::now('UTC')->addMinutes(24 * 60)->getTimestamp(),
        ];
        $payload = JWTFactory::claims($customClaims)->make(true);
        return JWTAuth::encode($payload)->get();
    }

    public function createRefreshToken()
    {
        $customClaims = [
            'iss' => null,
            'uid' => $this->id,
            'sub' => null,
            'typ' => 'refresh',
            'exp' => Carbon::now('UTC')->addMinutes(30 * 24 * 60)->getTimestamp(),
        ];
        $payload = JWTFactory::claims($customClaims)->make(true);
        return JWTAuth::encode($payload)->get();
    }

    /**
     * Informa se esse cliente faz parte da empresa informada
     */
    public function empresa()
    {
        return $this->belongsTo(Cliente::class, 'empresa_id');
    }

    /**
     * Retorna o cadastro como funcionário desse cliente
     */
    public function prestador()
    {
        return $this->hasOne(Prestador::class, 'cliente_id');
    }

    /**
     * Retorna o telefone principal do cliente
     */
    public function telefone()
    {
        return $this->hasOne(Telefone::class, 'cliente_id')
            ->orderBy('data_validacao', 'DESC')
            ->orderBy('principal', 'DESC')
            ->orderBy('id', 'DESC');
    }

    public function validate($old)
    {
        $errors = [];
        if ($this->tipo == self::TIPO_FISICA) {
            if (!Validator::checkCPF($this->cpf, true)) {
                $errors['cpf'] = __('messages.cpf_invalid');
            }
        } else {
            if (!Validator::checkCNPJ($this->cpf, true)) {
                $errors['cnpj'] = __('messages.cnpj_invalid');
            }
        }
        $empresa = $this->empresa;
        if (!is_null($empresa) && $empresa->tipo != self::TIPO_JURIDICA) {
            $errors['empresa'] = __('messages.must_be_company');
        }
        return $errors;
    }

    public function onInsert()
    {
        $errors = [];
        if (!is_null($this->ip)) {
            // evita muitos cadastros inválidos por dia de uma mesma pessoa
            $registro = self::where('status', '<>', self::STATUS_ATIVO)
                ->where('ip', $this->ip)
                ->where('data_cadastro', '>', Carbon::parse('-1 day'));
            if ($registro->exists()) {
                $errors['status'] = __('messages.account_already_created');
            }
        }
        return $errors;
    }

    /**
     * Obtém o nome completo da pessoa física ou o nome fantasia da empresa
     */
    public function getNomeCompleto()
    {
        if ($this->tipo == self::TIPO_JURIDICA) {
            return $this->nome;
        }
        return trim($this->nome . ' ' . $this->sobrenome);
    }

    /**
     * Informa se esse cliente é dono da empresa
     *
     * @return boolean
     */
    public function isOwner()
    {
        return !is_null(app('business')->empresa_id) && app('business')->empresa_id == $this->empresa_id;
    }

    /**
     * Verifica se o cliente tem acesso para a permissão informada
     *
     * @param string $permissao
     * @return boolean
     */
    public function hasPermissionTo(string $permissao)
    {
        if ($this->isOwner()) {
            return true;
        }
        $prestador = $this->prestador;
        if (is_null($prestador)) {
            return false;
        }
        return $prestador->funcao->hasPermissionTo($permissao);
    }
}

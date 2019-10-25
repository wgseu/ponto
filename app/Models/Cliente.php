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

use App\Util\Validator;
use App\Concerns\ModelEvents;
use App\Exceptions\SafeValidationException;
use Illuminate\Foundation\Auth\User;
use App\Interfaces\ValidateInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Interfaces\AuthorizableInterface;

/**
 * Informações de cliente físico ou jurídico. Clientes, empresas,
 * funcionários, fornecedores e parceiros são cadastrados aqui
 */
class Cliente extends User implements ValidateInterface, JWTSubject, AuthorizableInterface
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
        'twitter',
        'linkedin_url',
        'imagem_url',
        'linguagem',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'senha', 'secreto',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_FISICA,
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
        return [];
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

    /**
     * Informa se esse cliente faz parte da empresa informada
     */
    public function empresa()
    {
        return $this->belongsTo('App\Models\Cliente', 'empresa_id');
    }

    public function validate()
    {
        $errors = [];
        if ($this->tipo == self::TIPO_FISICA) {
            if (!Validator::checkCPF($this->cpf)) {
                $errors['cpf'] = __('messages.cpf_invalid');
            }
        } else {
            if (!Validator::checkCNPJ($this->cnpj)) {
                $errors['cnpj'] = __('messages.cnpj_invalid');
            }
        }
        if ($this->empresa_id) {
            $empresa = Empresa::find($this->empresa_id);
            if (!$empresa) {
                $errors['empresa'] = __('messages.not_found_company');
            }
        }
        if (!empty($errors)) {
            throw SafeValidationException::withMessages($errors);
        }
    }

    /**
     * Informa se esse cliente é dono da empresa
     *
     * @return boolean
     */
    public function isOwner()
    {
        $empresa = Empresa::find('1');
        return is_null($empresa)
            || is_null($empresa->empresa_id)
            || $empresa->empresa_id == $this->empresa_id;
    }

    /**
     * Verifica se o cliente tem acesso para a permissão informada
     *
     * @param string $permissao
     * @return boolean
     */
    public function hasPermissionTo(string $permissao)
    {
        return $this->isOwner() || true;
    }
}

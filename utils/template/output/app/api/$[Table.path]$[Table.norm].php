<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
$[table.if(package)]
namespace $[Table.package];
$[table.end]

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

$[table.if(comment)]
/**
$[table.each(comment)]
 * $[Table.comment]
$[table.end]
 */
$[table.end]
class $[Table.norm]$[table.if(inherited)] extends $[table.inherited]$[table.end]

{
$[field.each(all)]
$[field.if(primary)]
$[field.else.if(enum)]
$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     */
$[field.end]
$[field.each(option)]
    const $[FIELD.unix]_$[FIELD.option.norm] = '$[field.option]';
$[field.end]

$[field.end]
$[field.end]
$[field.each(all)]
$[field.if(repeated)]
$[field.else]
$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     * @var $[field.if(integer|bigint)]int$[field.else.if(float|double|currency)]float$[field.else]string$[field.end]$[field.if(array)][]$[field.end]

     */
$[field.end]
    private $$[field.unix]$[field.if(array)] = []$[field.end];
$[field.end]
$[field.end]

    /**
     * Constructor for a new empty instance of $[Table.norm]
     * @param array $$[table.unix] All field and values to fill the instance
     */
    public function __construct($$[table.unix] = [])
    {
$[table.if(inherited)]
        parent::__construct($$[table.unix]);
$[table.else]
        $this->fromArray($$[table.unix]);
$[table.end]
    }
$[field.each(all)]
$[field.if(repeated)]
$[field.else]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
$[field.if(array)]
     * @param int $number number to get $[Field.norm]
$[field.end]
$[field.if(integer|bigint)]
     * @return int $[field.name] of $[Table.name]
$[field.else.if(float|double|currency)]
     * @return float $[field.name] of $[Table.name]
$[field.else]
     * @return string $[field.name] of $[Table.name]
$[field.end]
     */
$[field.end]
    public function get$[Field.norm]($[field.if(array)]$number$[field.end])
    {
$[field.if(array)]
        if ($number < 1 || $number > $[field.array.count]) {
            throw new \Exception(
                _t('invalid_field_index', intval($number), 1, $[field.array.count]),
                500
            );
        }
$[field.end]
        return $this->$[field.unix]$[field.if(array)][$number]$[field.end];
    }
$[field.if(boolean)]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     * @return boolean Check if $[field.gender] of $[Field.norm] is selected or checked
     */
$[field.end]
    public function is$[Field.norm]()
    {
        return $this->$[field.unix] == 'Y';
    }
$[field.end]

    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
$[field.if(array)]
     * @param int $number number to set $[field.name]
$[field.end]
$[field.if(integer|bigint)]
     * @param int $$[field.unix] Set $[field.name] for $[Table.name]
$[field.else.if(float|double|currency)]
     * @param float $$[field.unix] Set $[field.name] for $[Table.name]
$[field.else]
     * @param string $$[field.unix] Set $[field.name] for $[Table.name]
$[field.end]
     * @return self Self instance
     */
    public function set$[Field.norm]($[field.if(array)]$number, $[field.end]$$[field.unix])
    {
$[field.if(array)]
        if ($number < 1 || $number > $[field.array.count]) {
            throw new \Exception(
                _t('invalid_field_index', intval($number), 1, $[field.array.count]),
                500
            );
        }
$[field.end]
        $this->$[field.unix]$[field.if(array)][$number]$[field.end] = $$[field.unix];
        return $this;
    }
$[field.end]
$[field.end]

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
$[table.if(inherited)]
        $$[table.unix] = parent::toArray($recursive);
$[table.else]
        $$[table.unix] = [];
$[table.end]
$[field.each(all)]
        $$[table.unix]['$[field]'] = $this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]);
$[field.end]
        return $$[table.unix];
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $$[table.unix] Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($$[table.unix] = [])
    {
        if ($$[table.unix] instanceof self) {
            $$[table.unix] = $$[table.unix]->toArray();
        } elseif (!is_array($$[table.unix])) {
            $$[table.unix] = [];
        }
$[table.if(inherited)]
        parent::fromArray($$[table.unix]);
$[table.end]
$[field.each(all)]
$[field.if(null)]
        if (!array_key_exists('$[field]', $$[table.unix])) {
$[field.else]
        if (!isset($$[table.unix]['$[field]'])) {
$[field.end]
$[field.if(boolean)]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]'N');
$[field.else.if(info)]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]null);
$[field.end]
        } else {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[table.unix]['$[field]']);
        }
$[field.end]
        return $this;
    }
$[field.each(all)]
$[field.if(image|blob)]

    /**
     * Get relative $[field.name] path or default $[field.name]
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for $[table.name] $[field.name]
     */
    public function make$[Field.norm]($default = false, $default_name = '$[table.unix].png')
    {
        $$[field.unix] = $this->get$[Field.norm]();
        if ($default) {
            $$[field.unix] = null;
        }
        return get_image_url($$[field.unix], '$[field.image.folder]', $default_name);
    }
$[field.end]
$[field.end]

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $$[table.unix] = parent::publish($requester);
$[field.each(all)]
$[field.if(image|blob)]
        $$[table.unix]['$[field]'] = $this->make$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]false, null);
$[field.else.if(masked)]
$[field.contains(fone)]
        $$[table.unix]['$[field]'] = Mask::phone($$[table.unix]['$[field]']);
$[field.else.match(cpf)]
        $$[table.unix]['$[field]'] = Mask::cpf($$[table.unix]['$[field]']);
$[field.else.match(cep)]
        $$[table.unix]['$[field]'] = Mask::cep($$[table.unix]['$[field]']);
$[field.else.match(cnpj)]
        $$[table.unix]['$[field]'] = Mask::cnpj($$[table.unix]['$[field]']);
$[field.else]
        $$[table.unix]['$[field]'] = Mask::mask($$[table.unix]['$[field]'], _p('$[field.unix].mask'));
$[field.end]
$[field.else.match(ip|senha|password|secreto|salt|deletado)]
        unset($$[table.unix]['$[field]']);
$[field.end]
$[field.end]
        return $$[table.unix];
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
$[field.each(all)]
$[field.if(primary)]
        $this->set$[Field.norm]($original->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.else.if(date)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::date($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(time)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::time($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(datetime)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::datetime($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(currency)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::money($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), $localized));
$[field.else.if(float|double)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::float($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), $localized));
$[field.else.if(masked)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::unmask($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), _p('Mascara', '$[Field.norm]')));
$[field.else.if(integer|bigint)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::number($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(blob)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (is_null($$[field.unix]) && trim($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]true);
        } else {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix]);
            $$[field.unix]_path = app()->getPath('public') . $this->make$[Field.norm]();
            if (!is_null($$[field.unix])) {
                $this->set$[Field.norm](file_get_contents($$[field.unix]_path));
                @unlink($$[field.unix]_path);
            }
        }
$[field.else.if(image)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (is_null($$[field.unix]) && trim($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$original->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
        } else {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix]);
        }
$[field.else.if(text)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::text($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(string|enum)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::string($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.end]
$[field.end]
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
$[field.each(all)]
$[field.if(blob)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.else.if(image)]
        if (!is_null($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) && $dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]) != $this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) {
            @unlink(get_image_path($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), '$[field.image.folder]'));
        }
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.end]
$[field.end]
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of $[Table.norm] in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
$[field.each(all)]
$[field.if(primary)]
$[field.else.contains(fone)]
        if (!Validator::checkPhone($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[table.unix].$[field.unix]_invalid');
        }
$[field.else.match(cpf)]
        if (!Validator::checkCPF($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(cep)]
        if (!Validator::checkCEP($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(cnpj)]
        if (!Validator::checkCNPJ($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(usuario|login)]
        if (!Validator::checkUsername($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid');
        }
$[field.else.match(email)]
        if (!Validator::checkEmail($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid');
        }
$[field.else.match(ip)]
        if (!Validator::checkIP($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = _t('$[field.unix]_invalid');
        }
$[field.else.match(senha|password)]
        if (!Validator::checkPassword($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), $this->exists())) {
            $errors['$[field]'] = _t('$[field.unix]_insecure');
        }
$[field.else.if(enum)]
        if (!Validator::checkInSet($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), self::get$[Field.norm]Options())) {
            $errors['$[field]'] = _t('$[table.unix].$[field.unix]_invalid');
        }
$[field.else.if(boolean)]
        if (!Validator::checkBoolean($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
            $errors['$[field]'] = _t('$[table.unix].$[field.unix]_invalid');
        }
$[field.else.match(.*atualizacao|.*cadastro|.*criacao|.*lancamento|.*envio)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]DB::now());
$[field.else.if(null)]
$[field.else]
        if (is_null($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
            $errors['$[field]'] = _t('$[table.unix].$[field.unix]_cannot_empty');
        }
$[field.end]
$[field.end]
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
$[table.each(unique)]
        if (contains([$[unique.each(all)]'$[Field]', $[unique.end]'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
$[unique.each(all)]
                '$[field]' => _t(
                    '$[table.unix].$[field.unix]_used',
                    $this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])
                ),
$[unique.end]
            ]);
        }
$[table.end]
$[table.if(inherited)]
        return parent::translate($e);
$[table.else]
        return $e;
$[table.end]
    }
$[table.each(unique)]

    /**
     * Load into this object from database using$[unique.each(all)], $[Field.norm]$[unique.end]

     * @return self Self filled instance or empty when not found
     */
    public function loadBy$[unique.each(all)]$[Field.norm]$[unique.end]()
    {
        return $this->load([
$[unique.each(all)]
$[field.if(integer|bigint)]
            '$[field]' => intval($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])),
$[field.else.if(float|double|currency)]
            '$[field]' => floatval($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])),
$[field.else]
            '$[field]' => strval($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])),
$[field.end]
$[unique.end]
        ]);
    }
$[table.end]
$[field.each(all)]
$[field.if(blob)]

    /**
     * Load $[field.name] data from blob field on database
     * @return self Self instance with $[field.name] field filled
     */
    public function load$[Field.norm]()
    {
        $data = DB::from('$[Table] $[table.letter]')
            ->select(null)
            ->select('$[table.letter].$[field]')
            ->where('$[table.letter].$[primary]', $this->get$[Primary.norm]())
            ->fetchColumn();
        return $this->set$[Field.norm]($data);
    }
$[field.end]
$[field.end]
$[field.each(all)]
$[field.if(reference)]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
$[field.if(array)]
     * @param int $number get $[Reference.norm] from number
$[field.end]
     * @return \$[Reference.package]\$[Reference.norm] The object fetched from database
     */
$[field.end]
    public function find$[Field.norm]($[field.if(array)]$number$[field.end])
    {
        return \$[Reference.package]\$[Reference.norm]::findBy$[Reference.pk.norm]($this->get$[Field.norm]($[field.if(array)]$number$[field.end]));
    }
$[field.end]
$[field.end]
$[field.each(all)]
$[field.if(primary)]
$[field.else.if(enum)]

    /**
     * Gets textual and translated $[Field.norm] for $[Table.norm]
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function get$[Field.norm]Options($index = null)
    {
        $options = [
$[field.each(option)]
            self::$[FIELD.unix]_$[FIELD.option.norm] => _t('$[table.unix].$[field.unix]_$[field.option.unix]'),
$[field.end]
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }
$[field.end]
$[field.end]
$[descriptor.if(string)]

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '$[table.letter].$[descriptor] LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, '$[table.letter].');
    }
$[descriptor.end]

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
$[table.exists(blob)]
        $query = DB::from('$[Table] $[table.letter]')
            ->select(null)$[field.each(all)]
$[field.if(blob)]

            ->select(
                '(CASE WHEN $[table.letter].$[field] IS NULL THEN NULL ELSE '.
                DB::concat(['$[table.letter].$[primary]', '".png"']).
                ' END) as imagem'
            )$[field.else]

            ->select('$[table.letter].$[field]')$[field.end]
$[field.end];
$[table.else]
        $query = DB::from('$[Table] $[table.letter]');
$[table.end]
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
$[descriptor.if(string)]
        $query = $query->orderBy('$[table.letter].$[descriptor] ASC');
$[descriptor.end]
        $query = $query->orderBy('$[table.letter].$[primary] ASC');
        return DB::buildCondition($query, $condition);
    }
$[table.each(unique)]

    /**
     * Find this object on database using$[unique.each(all)], $[Field.norm]$[unique.end]

$[unique.each(all)]
$[field.if(integer|bigint)]
     * @param int $$[field.unix] $[field.name] to find $[Table.name]
$[field.else.if(float|double|currency)]
     * @param float $$[field.unix] $[field.name] to find $[Table.name]
$[field.else]
     * @param string $$[field.unix] $[field.name] to find $[Table.name]
$[field.end]
$[unique.end]
     * @return self A filled instance or empty when not found
     */
    public static function findBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[field.unix]$[unique.end])
    {
        $result = new self();
$[unique.each(all)]
        $result->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix]);
$[unique.end]
        return $result->loadBy$[unique.each(all)]$[Field.norm]$[unique.end]();
    }
$[table.end]
}

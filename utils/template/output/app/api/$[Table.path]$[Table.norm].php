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
     * @param integer $number number to get $[Field.norm]
$[field.end]
$[field.if(integer|bigint)]
     * @return int $[field.name] of $[Table.name]
$[field.else.if(float|double)]
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
     * Set $[Field.norm] value to new on param
$[field.if(array)]
     * @param integer $number number to set $[field.name]
$[field.end]
$[field.if(integer|bigint)]
     * @param int $$[field.unix] Set $[field.name] for $[Table.name]
$[field.else.if(float|double)]
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
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $$[table.unix] = parent::publish();
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
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
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
$[field.else.if(string)]
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

    /**
     * Insert a new $[Table.name] into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->set$[Primary.norm](null);
        $values = $this->validate();
        unset($values['$[primary]']);
        try {
            $$[primary.unix] = DB::insertInto('$[Table]')->values($values)->execute();
            $this->set$[Primary.norm]($$[primary.unix]);
            $this->loadBy$[Primary.norm]();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update $[Table.name] with instance values into database for $[Primary.name]
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('$[table.unix].id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
$[field.each(all)]
$[field.if(blob)]
        if ($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]) === true) {
            unset($values['$[field]']);
        }
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio)]
        unset($values['$[field]']);
$[field.end]
$[field.end]
$[field.end]
        try {
            $affected = DB::update('$[Table]')
                ->set($values)
                ->where(['$[primary]' => $this->get$[Primary.norm]()])
                ->execute();
            $this->loadBy$[Primary.norm]();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using $[Primary.name]
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('$[table.unix].id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('$[Table]')
            ->where('$[primary]', $this->get$[Primary.norm]())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
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
$[field.else.if(float|double)]
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
     * @param integer $number get $[Reference.norm] from number
$[field.end]
     * @return \$[Reference.package]\$[Reference.norm] The object fetched from database
     */
$[field.end]
    public function find$[Field.norm]($[field.if(array)]$number$[field.end])
    {
$[field.if(null)]
        if (is_null($this->get$[Field.norm]($[field.if(array)]$number$[field.end]))) {
            return new \$[Reference.package]\$[Reference.norm]();
        }
$[field.end]
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

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $$[table.unix] = new self();
        $allowed = Filter::concatKeys('$[table.letter].', $$[table.unix]->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, '$[table.letter].');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
$[descriptor.if(string)]
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '$[table.letter].$[descriptor] LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
$[descriptor.end]
        return Filter::keys($condition, $allowed, '$[table.letter].');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
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
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
$[descriptor.if(string)]
        $query = $query->orderBy('$[table.letter].$[descriptor] ASC');
$[descriptor.end]
        $query = $query->orderBy('$[table.letter].$[primary] ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled $[Table.name] or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled $[Table.name] or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('$[table.unix].not_found'), 404);
        }
        return $result;
    }
$[table.each(unique)]

    /**
     * Find this object on database using$[unique.each(all)], $[Field.norm]$[unique.end]

$[unique.each(all)]
$[field.if(integer|bigint)]
     * @param int $$[field.unix] $[field.name] to find $[Table.name]
$[field.else.if(float|double)]
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

    /**
     * Find all $[Table.name]
     * @param array  $condition Condition to get all $[Table.name]
     * @param array  $order     Order $[Table.name]
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as $[Table.norm]
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}

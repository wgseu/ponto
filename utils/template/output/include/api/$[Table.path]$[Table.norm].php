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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
$[table.if(package)]
namespace $[Table.package];
$[table.end]

use MZ\Util\Filter;
use MZ\Util\Validator;

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
     * @param  integer $index index to get $[Field.norm]
$[field.end]
     * @return mixed $[Field.name] of $[Table.norm]
     */
$[field.end]
    public function get$[Field.norm]($[field.if(array)]$index$[field.end])
    {
$[field.if(array)]
        if ($index < 1 || $index > $[field.array.count]) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    [intval($index), 1, $[field.array.count]]
                ),
                500
            );
        }
$[field.end]
        return $this->$[field.unix]$[field.if(array)][$index]$[field.end];
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
     * @param  integer $index index for set $[Field.norm]
$[field.end]
     * @param  mixed $$[field.unix] new value for $[Field.norm]
     * @return $[Table.norm] Self instance
     */
    public function set$[Field.norm]($[field.if(array)]$index, $[field.end]$$[field.unix])
    {
$[field.if(array)]
        if ($index < 1 || $index > $[field.array.count]) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    [intval($index), 1, $[field.array.count]]
                ),
                500
            );
        }
$[field.end]
        $this->$[field.unix]$[field.if(array)][$index]$[field.end] = $$[field.unix];
        return $this;
    }
$[field.end]
$[field.end]

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
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
     * @param  mixed $$[table.unix] Associated key -> value to assign into this instance
     * @return $[Table.norm] Self instance
     */
    public function fromArray($$[table.unix] = [])
    {
        if ($$[table.unix] instanceof $[Table.norm]) {
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
$[field.if(info)]
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
     * @return string relative web path for $[table.name] $[field.name]
     */
    public function make$[Field.norm]($default = false)
    {
        $$[field.unix] = $this->get$[Field.norm]();
        if ($default) {
            $$[field.unix] = null;
        }
        return get_image_url($$[field.unix], '$[field.image.folder]', '$[table.unix].png');
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
        $$[table.unix]['$[field]'] = $this->make$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]);
$[field.else.if(masked)]
        $$[table.unix]['$[field]'] = \MZ\Util\Mask::cep($$[table.unix]['$[field]']);
$[field.else.match(ip|senha|password|secreto|salt|deletado)]
        unset($$[table.unix]['$[field]']);
$[field.end]
$[field.end]
        return $$[table.unix];
    }

    /**
     * Filter fields, upload data and keep key data
     * @param $[Table.norm] $original Original instance without modifications
     */
    public function filter($original)
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
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::money($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(float|double)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::float($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(masked)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::unmask($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), _p('Mascara', '$[Field.norm]')));
$[field.else.if(integer|bigint)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::number($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(image)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (is_null($$[field.unix]) && trim($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$original->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
        } else {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix]);
        }
$[field.else.if(blob)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (!is_null($$[field.unix])) {
            $$[field.unix]_path = get_image_path($$[field.unix], '$[field.image.folder]');
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]file_get_contents($$[field.unix]_path));
            unlink($$[field.unix]_path);
        } elseif (trim($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]true);
        }
$[field.else.if(text)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::text($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(string)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::string($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.end]
$[field.end]
    }

    /**
     * Clean instance resources like images and docs
     * @param  $[Table.norm] $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
$[field.each(all)]
$[field.if(image)]
        if (!is_null($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) && $dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]) != $this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])) {
            unlink(get_image_path($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), '$[field.image.folder]'));
        }
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.else.if(blob)]
        $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.end]
$[field.end]
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of $[Table.norm] in array format
     */
    public function validate()
    {
        $errors = [];
$[field.each(all)]
$[field.if(primary)]
$[field.else]
$[field.if(null)]
$[field.if(info)]
        if (is_null($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
$[field.if(boolean)]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]'N');
$[field.else]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.end]
        }
$[field.end]
$[field.else]
        if (is_null($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
$[field.if(info)]
$[field.if(boolean)]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]'N');
$[field.else]
            $this->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.end]
$[field.else]
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] não pode ser vazi$[field.gender]';
$[field.end]
        }
$[field.end]
$[field.contains(fone)]
        if (!Validator::checkPhone($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[FIELD.gender] $[Field.name] é inválid$[field.gender]';
        }
$[field.else.match(cpf)]
        if (!Validator::checkCPF($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = sprintf('$[FIELD.gender] %s é inválid$[field.gender]', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(cep)]
        if (!Validator::checkCEP($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = sprintf('$[FIELD.gender] %s é inválid$[field.gender]', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(cnpj)]
        if (!Validator::checkCNPJ($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = sprintf('$[FIELD.gender] %s é inválid$[field.gender]', _p('Titulo', '$[Field.name]'));
        }
$[field.else.match(usuario|login)]
        if (!Validator::checkUsername($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] é inválid$[field.gender]';
        }
$[field.else.match(email)]
        if (!Validator::checkEmail($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] é inválid$[field.gender]';
        }
$[field.else.match(ip)]
        if (!Validator::checkIP($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[FIELD.gender] $[Field.name] é inválid$[field.gender]';
        }
$[field.else.match(senha|password)]
        if (!Validator::checkPassword($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), true)) {
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] informad$[field.gender] não é segur$[field.gender]';
        }
$[field.end]
$[field.if(enum)]
        if (!Validator::checkInSet($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), self::get$[Field.norm]Options(), true)) {
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] é inválid$[field.gender]';
        }
$[field.else.if(boolean)]
        if (!Validator::checkBoolean($this->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]), true)) {
            $errors['$[field]'] = '$[FIELD.gender] $[field.name] é inválid$[field.gender]';
        }
$[field.end]
$[field.end]
$[field.end]
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
$[table.each(unique)]
        if (stripos($e->getMessage(), '$[Unique.name]') !== false) {
            return new \MZ\Exception\ValidationException([
$[unique.each(all)]
                '$[field]' => sprintf(
                    '$[FIELD.gender] $[field.name] "%s" já está cadastrad$[field.gender]',
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
     * @return $[Table.norm] Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['$[primary]']);
        try {
            $$[primary.unix] = self::getDB()->insertInto('$[Table]')->values($values)->execute();
            $$[table.unix] = self::findBy$[Primary.norm]($$[primary.unix]);
            $this->fromArray($$[table.unix]->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update $[Table.name] with instance values into database for $[Primary.name]
     * @return $[Table.norm] Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador d$[table.gender] $[table.name] não foi informado');
        }
        unset($values['$[primary]']);
        try {
            self::getDB()
                ->update('$[Table]')
                ->set($values)
                ->where('$[primary]', $this->get$[Primary.norm]())
                ->execute();
            $$[table.unix] = self::findBy$[Primary.norm]($this->get$[Primary.norm]());
            $this->fromArray($$[table.unix]->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using $[Primary.name]
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador d$[table.gender] $[table.name] não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('$[Table]')
            ->where('$[primary]', $this->get$[Primary.norm]())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return $[Table.norm] Self instance filled or empty
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

$[unique.each(all)]
$[field.if(integer|bigint)]
     * @param  int $$[field.unix] $[field.name] to find $[Table.name]
$[field.else.if(float|double)]
     * @param  float $$[field.unix] $[field.name] to find $[Table.name]
$[field.else]
     * @param  string $$[field.unix] $[field.name] to find $[Table.name]
$[field.end]
$[unique.end]
     * @return $[Table.norm] Self filled instance or empty when not found
     */
    public function loadBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[field.unix]$[unique.end])
    {
        return $this->load([
$[unique.each(all)]
$[field.if(integer|bigint)]
            '$[field]' => intval($$[field.unix]),
$[field.else.if(float|double)]
            '$[field]' => floatval($$[field.unix]),
$[field.else]
            '$[field]' => strval($$[field.unix]),
$[field.end]
$[unique.end]
        ]);
    }
$[table.end]
$[field.each(all)]
$[field.if(reference)]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     * @return \$[Reference.package]\$[Reference.norm] The object fetched from database
     */
$[field.end]
    public function find$[Field.norm]($[field.if(array)]$index$[field.end])
    {
$[field.if(null)]
        if (is_null($this->get$[Field.norm]($[field.if(array)]$index$[field.end]))) {
            return new \$[Reference.package]\$[Reference.norm]();
        }
$[field.end]
        return \$[Reference.package]\$[Reference.norm]::findBy$[Reference.pk.norm]($this->get$[Field.norm]($[field.if(array)]$index$[field.end]));
    }
$[field.end]
$[field.end]
$[field.each(all)]
$[field.if(primary)]
$[field.else.if(enum)]

    /**
     * Gets textual and translated $[Field.norm] for $[Table.norm]
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function get$[Field.norm]Options($index = null)
    {
        $options = [
$[field.each(option)]
            self::$[FIELD.unix]_$[FIELD.option.norm] => '$[Field.option.name]',
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
        $$[table.unix] = new $[Table.norm]();
        $allowed = Filter::concatKeys('$[table.letter].', $$[table.unix]->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, '$[table.letter].');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('$[Table] $[table.letter]');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
$[descriptor.if(string)]
        $query = $query->orderBy('$[table.letter].$[descriptor] ASC');
$[descriptor.end]
        $query = $query->orderBy('$[table.letter].$[primary] ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return $[Table.norm] A filled $[Table.name] or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new $[Table.norm]($row);
    }
$[table.each(unique)]

    /**
     * Find this object on database using$[unique.each(all)], $[Field.norm]$[unique.end]

$[unique.each(all)]
$[field.if(integer|bigint)]
     * @param  int $$[field.unix] $[field.name] to find $[Table.name]
$[field.else.if(float|double)]
     * @param  float $$[field.unix] $[field.name] to find $[Table.name]
$[field.else]
     * @param  string $$[field.unix] $[field.name] to find $[Table.name]
$[field.end]
$[unique.end]
     * @return $[Table.norm] A filled instance or empty when not found
     */
    public static function findBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[field.unix]$[unique.end])
    {
        return self::find([
$[unique.each(all)]
$[field.if(integer|bigint)]
            '$[field]' => intval($$[field.unix]),
$[field.else.if(float|double)]
            '$[field]' => floatval($$[field.unix]),
$[field.else]
            '$[field]' => strval($$[field.unix]),
$[field.end]
$[unique.end]
        ]);
    }
$[table.end]

    /**
     * Find all $[Table.name]
     * @param  array  $condition Condition to get all $[Table.name]
     * @param  array  $order     Order $[Table.name]
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as $[Table.norm]
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
            $result[] = new $[Table.norm]($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}

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
namespace $[tAble.package];
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
class $[tAble.norm]$[table.if(inherited)] extends $[table.inherited]$[table.end]

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
    private $$[field.unix]$[field.if(array)] = array()$[field.end];
$[field.end]
$[field.end]

    /**
     * Constructor for a new empty instance of $[tAble.norm]
     * @param array $$[table.unix] All field and values to fill the instance
     */
    public function __construct($$[table.unix] = array())
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
     * @param  integer $index index to get $[fIeld.norm]
$[field.end]
     * @return mixed $[fIeld.name] of $[tAble.norm]
     */
$[field.end]
    public function get$[fIeld.norm]($[field.if(array)]$index$[field.end])
    {
$[field.if(array)]
        if ($index < 1 || $index > $[field.array.count]) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    array(intval($index), 1, $[field.array.count])
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
     * @return boolean Check if $[field.gender] of $[fIeld.norm] is selected or checked
     */
$[field.end]
    public function is$[fIeld.norm]()
    {
        return $this->$[field.unix] == 'Y';
    }
$[field.end]

    /**
     * Set $[fIeld.norm] value to new on param
$[field.if(array)]
     * @param  integer $index index for set $[fIeld.norm]
$[field.end]
     * @param  mixed $$[field.unix] new value for $[fIeld.norm]
     * @return $[tAble.norm] Self instance
     */
    public function set$[fIeld.norm]($[field.if(array)]$index, $[field.end]$$[field.unix])
    {
$[field.if(array)]
        if ($index < 1 || $index > $[field.array.count]) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    array(intval($index), 1, $[field.array.count])
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
        $$[table.unix] = array();
$[table.end]
$[field.each(all)]
        $$[table.unix]['$[field]'] = $this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]);
$[field.end]
        return $$[table.unix];
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $$[table.unix] Associated key -> value to assign into this instance
     * @return $[tAble.norm] Self instance
     */
    public function fromArray($$[table.unix] = array())
    {
        if ($$[table.unix] instanceof $[tAble.norm]) {
            $$[table.unix] = $$[table.unix]->toArray();
        } elseif (!is_array($$[table.unix])) {
            $$[table.unix] = array();
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
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$[fIeld.info]);
$[field.else]
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]null);
$[field.end]
        } else {
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[table.unix]['$[field]']);
        }
$[field.end]
        return $this;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param $[tAble.norm] $original Original instance without modifications
     */
    public function filter($original)
    {
$[field.each(all)]
$[field.if(primary)]
        $this->set$[fIeld.norm]($original->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.else.if(date)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::date($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(time)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::time($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(datetime)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::datetime($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(currency)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::money($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(float|double)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::float($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(masked)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::unmask($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]), '$[fIeld.mask]'));
$[field.else.if(integer|bigint)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::number($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(image)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (is_null($$[field.unix]) && trim($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$original->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]));
        } else {
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix]);
        }
$[field.else.if(blob)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (!is_null($$[field.unix])) {
            $$[field.unix]_path = get_image_path($$[field.unix], '$[field.image.folder]');
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]file_get_contents($$[field.unix]_path));
            unlink($$[field.unix]_path);
        } elseif (trim($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])) != '') {
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]true);
        }
$[field.else.if(text)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::text($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.else.if(string)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]Filter::string($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])));
$[field.end]
$[field.end]
    }

    /**
     * Clean instance resources like images and docs
     * @param  $[tAble.norm] $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
$[field.each(all)]
$[field.if(image)]
        if (!is_null($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])) && $dependency->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]) != $this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])) {
            unlink(get_image_path($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]), '$[field.image.folder]'));
        }
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.else.if(blob)]
        $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$dependency->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]));
$[field.end]
$[field.end]
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of $[tAble.norm] in array format
     */
    public function validate()
    {
        $errors = array();
$[field.each(all)]
$[field.if(primary)]
$[field.else]
$[field.if(null)]
$[field.if(info)]
        if (is_null($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$[fIeld.info]);
        }
$[field.end]
$[field.else]
        if (is_null($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]))) {
$[field.if(info)]
            $this->set$[fIeld.norm]($[field.if(array)]$[field.array.number], $[field.end]$[fIeld.info]);
$[field.else]
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] não pode ser vazi$[field.gender]';
$[field.end]
        }
$[field.end]
$[field.contains(fone)]
        if (!Validator::checkPhone($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(cpf)]
        if (!Validator::checkCPF($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(cnpj)]
        if (!Validator::checkCNPJ($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(usuario|login)]
        if (!Validator::checkUsername($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(email)]
        if (!Validator::checkEmail($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(ip)]
        if (!Validator::checkIP($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])$[field.if(null)], true$[field.end])) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
        }
$[field.else.match(senha|password)]
        if (!Validator::checkPassword($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]), true)) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] informad$[field.gender] não é segur$[field.gender]';
        }
$[field.end]
$[field.if(enum)]
        if (!is_null($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end])) &&
            !array_key_exists($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]), self::get$[fIeld.norm]Options())
        ) {
            $errors['$[field]'] = '$[Field.gender] $[fIeld.name] é invalid$[field.gender]';
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
        if (stripos($e->getMessage(), '$[uNique.name]') !== false) {
            return new \MZ\Exception\ValidationException(array(
$[unique.each(all)]
                '$[field]' => vsprintf(
                    '$[Field.gender] $[fIeld.name] "%s" já está cadastrad$[field.gender]',
                    array($this->get$[fIeld.norm]($[field.if(array)]$[field.array.number]$[field.end]))
                ),
$[unique.end]
            ));
        }
$[table.end]
$[table.if(inherited)]
        return parent::translate($e);
$[table.else]
        return $e;
$[table.end]
    }
$[field.each(all)]
$[field.if(enum)]

    /**
     * Gets textual and translated $[fIeld.norm] for $[tAble.norm]
     * @return array A associative key -> translated representative text
     */
    public static function get$[fIeld.norm]Options()
    {
        return array(
$[field.each(option)]
            self::$[FIELD.unix]_$[FIELD.option.norm] => '$[fIeld.option.name]'),
$[field.end]
        );
    }
$[field.end]
$[field.end]
$[table.each(unique)]

    /**
     * Find this object on database using$[unique.each(all)], $[fIeld.norm]$[unique.end]

$[unique.each(all)]
$[field.if(integer|bigint)]
     * @param  int $$[field.unix] $[field.name] to find $[tAble.name]
$[field.else.if(float|double)]
     * @param  float $$[field.unix] $[field.name] to find $[tAble.name]
$[field.else]
     * @param  string $$[field.unix] $[field.name] to find $[tAble.name]
$[field.end]
$[unique.end]
     * @return $[tAble.norm] A filled instance or empty when not found
     */
    public static function findBy$[unique.each(all)]$[fIeld.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[field.unix]$[unique.end])
    {
        return self::find(array(
$[unique.each(all)]
$[field.if(integer|bigint)]
            '$[field]' => intval($$[field.unix]),
$[field.else.if(float|double)]
            '$[field]' => floatval($$[field.unix]),
$[field.else]
            '$[field]' => strval($$[field.unix]),
$[field.end]
$[unique.end]
        ));
    }
$[table.end]

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = array())
    {
        $query = self::getDB()->from('$[tAble]');
        return $query->where($condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @return $[tAble.norm] A filled $[tAble.name] or empty instance
     */
    public static function find($condition)
    {
        $query = self::query($condition)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = array();
        }
        return new $[tAble.norm]($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
     */
    public static function findAll($condition = array(), $limit = null, $offset = null)
    {
        $query = self::query($condition);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = array();
        foreach ($rows as $row) {
            $result[] = new $[tAble.norm]($row);
        }
        return $result;
    }

    /**
     * Insert a new $[tAble.name] into the database and fill instance from database
     * @return $[tAble.norm] Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['$[primary]']);
        try {
            $$[primary.unix] = self::getDB()->insertInto('$[tAble]')->values($values)->execute();
            $$[table.unix] = self::findBy$[pRimary.norm]($$[primary.unix]);
            $this->fromArray($$[table.unix]->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update $[tAble.name] with instance values into database for $[pRimary.name]
     * @return $[tAble.norm] Self instance
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
                ->update('$[tAble]')
                ->set($values)
                ->where('$[primary]', $this->get$[pRimary.norm]())
                ->execute();
            $$[table.unix] = self::findBy$[pRimary.norm]($this->get$[pRimary.norm]());
            $this->fromArray($$[table.unix]->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using $[pRimary.name]
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador d$[table.gender] $[table.name] não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('$[tAble]')
            ->where('$[primary]', $this->get$[pRimary.norm]())
            ->execute();
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = array())
    {
        $query = self::query($condition);
        return $query->count();
    }
$[field.each(all)]
$[field.if(reference)]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     * @return \$[rEference.package]\$[rEference.norm] The object fetched from database
     */
$[field.end]
    public function find$[fIeld.norm]($[field.if(array)]$index$[field.end])
    {
$[field.if(null)]
        if (is_null($this->get$[fIeld.norm]($[field.if(array)]$index$[field.end]))) {
            return new \$[rEference.package]\$[rEference.norm]();
        }
$[field.end]
        return \$[rEference.package]\$[rEference.norm]::findBy$[rEference.pk.norm]($this->get$[fIeld.norm]($[field.if(array)]$index$[field.end]));
    }
$[field.end]
$[field.end]
}

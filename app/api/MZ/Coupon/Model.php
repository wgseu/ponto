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
namespace MZ\Coupon;

use Thermal\Printer;
use MZ\Util\Mask;
use MZ\Database\DB;

/**
 * Coupon model
 */
abstract class Model
{
    /**
     * Printer instance
     * @var \Thermal\Printer
     */
    private $printer;

    /**
     * Printer instance
     * @var array
     */
    private $template;

    /**
     * Current date and time
     * @var string
     */
    private $datetime;

    /**
     * Constructor for a new empty instance of Model
     * @param \Thermal\Printer $printer printer to print coupon
     */
    public function __construct($printer)
    {
        $this->printer = $printer;
        $this->template = [];
        $this->datetime = DB::now();
    }

    public function loadTemplate($name)
    {
        global $app;
        $path = $app->getSystem()->getSettings()->getValue('path', 'template');
        $content = file_get_contents($path . '/coupon/' . $name . '.cpt');
        $template = json_decode($content, true);
        $this->setTemplate($template['template']);
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param string $list list name
     * @param int $position set index of current item
     */
    protected function setCursor($list, $position)
    {
        return 0;
    }

    /**
     * Check if resource is available for printing
     * @param string $resource resource name to check
     * @return bool true for available, false otherwise
     */
    protected function isAvailable($resource)
    {
        global $app;

        if ($resource == 'company.cellphone') {
            return $app->getSystem()->getCompany()->getFone(2) !== null;
        }
        return false;
    }

    public function setDateTime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    protected function resolve($entry)
    {
        global $app;

        if ($entry == 'date.time') {
            return Mask::datetime($this->datetime);
        }
        if ($entry == 'company.name') {
            return $app->getSystem()->getCompany()->getNome();
        }
        if ($entry == 'company.address.street') {
            return $app->getSystem()->getLocalization()->getLogradouro();
        }
        if ($entry == 'company.address.number') {
            return $app->getSystem()->getLocalization()->getNumero();
        }
        if ($entry == 'company.address.district') {
            return $app->getSystem()->getDistrict()->getNome();
        }
        if ($entry == 'company.phone') {
            return Mask::phone($app->getSystem()->getCompany()->getFone(1));
        }
        if ($entry == 'company.cellphone') {
            return Mask::phone($app->getSystem()->getCompany()->getFone(2));
        }
        if ($entry == 'company.address.city') {
            return $app->getSystem()->getCity()->getNome();
        }
        if ($entry == 'company.address.state.code') {
            return $app->getSystem()->getState()->getUF();
        }
        if ($entry == 'money.symbol') {
            return $app->getSystem()->getCurrency()->getSimbolo();
        }
        return $entry;
    }

    private function printLines($text, $style, $columns)
    {
        if ($text === false) {
            return false;
        }
        $len = mb_strlen($text);
        $index = 0;
        while ($index < $len) {
            $copy_len = min($columns, $len - $index);
            $copy = mb_substr($text, $index, $copy_len);
            $this->printer->writeln($copy, $style);
            $index += $copy_len;
        }
        if ($len == 0) {
            $this->printer->feed();
        }
    }

    private function processLine($statement, $columns, $style, $width)
    {
        $spacing = 0;
        $text = '';
        if (isset($statement['items'])) {
            $text = $this->processStatement($statement['items'], $columns, $style, $width);
            if ($text === false) {
                return false;
            }
        } elseif (isset($statement['whitespace'])) {
            $spacing = $columns;
        }
        if (isset($statement['format'])) {
            $text = sprintf($statement['format'], $text);
        }
        $whitespace = ' ';
        if (isset($statement['whitespace'])) {
            $whitespace = $statement['whitespace'];
        }
        if (isset($statement['align'])) {
            if ($statement['align'] == 'center') {
                $spacing = (int)(($columns - mb_strlen($text)) / 2);
                if ($whitespace != ' ') {
                    $text = $text . str_repeat($whitespace, max(0, $spacing));
                }
            } elseif ($statement['align'] == 'right') {
                $spacing = $columns - mb_strlen($text);
                if ($spacing < 0) {
                    $spacing = $columns + $width - mb_strlen($text) % $width;
                }
            }
        }
        return str_repeat($whitespace, max(0, $spacing)) . $text;
    }

    /**
     * Print coupon
     */
    private function processStatement($statement, $columns, $style, $width)
    {
        if (!is_array($statement)) {
            return $this->resolve($statement);
        }
        reset($statement);
        if (\is_numeric(key($statement))) {
            $text = '';
            foreach ($statement as $stmt) {
                $result = $this->processStatement($stmt, $columns, $style, $width);
                if ($result === false) {
                    continue;
                }
                $text .= $result;
                if (mb_strlen($text) > $columns) {
                    // calculate free new lines spacing
                    $columns = $width - mb_strlen($text) % $width;
                } else {
                    // same line space remaining
                    $columns -= mb_strlen($result);
                }
            }
            return $text;
        }
        if (isset($statement['required']) && !$this->isAvailable($statement['required'])) {
            return false;
        }
        if (!isset($statement['list'])) {
            return $this->processLine($statement, $columns, $style, $width);
        }
        $count = $this->setCursor($statement['list'], 0);
        for ($i = 0; $i < $count; $i++) {
            $this->setCursor($statement['list'], $i);
            $text = $this->processLine($statement, $columns, $style, $width);
            $this->printLines($text, $style, $width);
        }
        return false;
    }

    /**
     * Print coupon
     */
    public function printCoupon()
    {
        $last = count($this->template) - 1;
        foreach ($this->template as $index => $stmt) {
            $style = 0;
            $columns = $this->printer->getColumns($style);
            if (is_array($stmt)) {
                if (isset($stmt['width']) && $stmt['width'] == '2x') {
                    $columns = (int)($columns / 2);
                    // $columns = $this->printer->getColumns($style);
                    $style |= Printer::STYLE_DOUBLE_WIDTH;
                }
                if (isset($stmt['height']) && $stmt['height'] == '2x') {
                    $style |= Printer::STYLE_DOUBLE_HEIGHT;
                }
                if (isset($stmt['style'])) {
                    $styles = explode('+', $stmt['style']);
                    foreach ($styles as $name) {
                        if ($name == 'bold') {
                            $style |= Printer::STYLE_BOLD;
                        } elseif ($name == 'italic') {
                            $style |= Printer::STYLE_ITALIC;
                        } elseif ($name == 'underline') {
                            $style |= Printer::STYLE_UNDERLINE;
                        } elseif ($name == 'condensed') {
                            $style |= Printer::STYLE_CONDENSED;
                            $columns = (int)($columns * 4 / 3);
                            // $columns = $this->printer->getColumns($style);
                        }
                    }
                }
            }
            $text = $this->processStatement($stmt, $columns, $style, $columns);
            $this->printLines($text, $style, $columns);
        }
    }
}

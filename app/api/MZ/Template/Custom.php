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
namespace MZ\Template;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Mask;

/**
 * Proccess template files
 */
class Custom extends Engine
{
    /**
     * Render a template file and returns it's content
     * @param  string $tFile template name
     * @return string template content processed
     */
    public function render($tFile)
    {
        ob_start();
        foreach ($this->getProperties() as $k => $v) {
            ${$k} = $v;
        }
        include $this->__template($tFile);
        return ob_get_clean();
    }

    /**
     * Get values from _GET variable
     * @param  string $key key to retrieve value
     * @param  string $default default value when key not found
     * @return mixed string or default value
     */
    public function get($key, $default = null)
    {
        return app()->getRequest()->query->get($key, $default);
    }

    /**
     * Get values from _POST variable
     * @param  string $key key to retrieve value
     * @param  string $default default value when key not found
     * @return mixed string or default value
     */
    public function post($key, $default = null)
    {
        return app()->getRequest()->request->get($key, $default);
    }

    /**
     * Escape value for secure echo
     * @param  string $value value to be escaped
     * @return string value escaped
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Format datetime string
     * @param  string $value value to be formatted
     * @return string datetime formatted
     */
    public function datetime($value)
    {
        return Mask::datetime($value);
    }

    /**
     * Format date or datetime string
     * @param  string $value value to be formatted
     * @return string date formatted
     */
    public function date($value)
    {
        return Mask::date($value);
    }

    /**
     * Convert database time format into user country time format
     * @param  string $value time into database format
     * @return string        time into readable user
     */
    public function time($value)
    {
        return Mask::time($value);
    }

    /**
     * Humman elapsed time
     * @param  string $value date or datetime to be converted
     * @return string humman elapsed time
     */
    public function elapsed($value)
    {
        return human_date($value);
    }

    /**
     * Format float value to money format
     * @param  string $value value to be formatted
     * @return string money formatted
     */
    public function money($value)
    {
        return Mask::money($value);
    }

    /**
     * Format float value to country currency format
     * @param  string $value value to be formatted
     * @return string currency formatted
     */
    public function currency($value)
    {
        return Mask::money($value, true);
    }

    /**
     * Convert boolean value into yes no text
     * @param  mixed $value boolean value
     * @return string boolean value into yes no text
     */
    public function bool($value)
    {
        return Mask::bool($value, true);
    }

    /**
     * Mask any text using a mask format
     * @param  string $str  texto to be masked
     * @param  string $mask mask to apply
     * @return string       Text with mask applied
     */
    public function mask($value, $mask)
    {
        return Mask::mask($value, $mask);
    }

    private function __parseecho($matches)
    {
        if (count($matches) <= 2) {
            return $this->__replace('<?php echo $this->escape(' . $matches[1] . '); ?>');
        }
        if ($matches[2] == 'raw') {
            return $this->__replace('<?php echo ' . $matches[1] . '; ?>');
        }
        return $this->__replace('<?php echo $this->escape(' . $matches[1] . '); ?>');
    }

    private function __parsestmt($matches)
    {
        return $this->__replace('<?php ' . $matches[1] . '; ?>');
    }

    private function __parseelseif($matches)
    {
        return $this->__replace('<?php } else if(' . $matches[1] . ') { ?>');
    }

    private function __parseloop($matches)
    {
        return $this->__replace(
            "<?php if(is_array($matches[2])){foreach($matches[2] AS $matches[1]) { ?>$matches[3]<?php }} ?>"
        );
    }

    private function __parseloopkeyval($matches)
    {
        return $this->__replace(
            "<?php if(is_array($matches[3])){".
            "foreach($matches[3] AS $matches[1]=>$matches[2]) { ?>$matches[4]<?php }} ?>"
        );
    }

    private function __parseif($matches)
    {
        return $this->__replace("<?php if($matches[1]) { ?>$matches[2]<?php } ?>");
    }

    private function __parse($tFile, $cFile)
    {
        $fileContent = file_get_contents($tFile);
        if ($fileContent === false) {
            throw new \Exception('Can\'t get content of template file "'.$tFile.'"', 500);
        }
        $fileContent = trim(preg_replace('/(}}|%}|>)\s+({{|{%|<)/', '$1$2', $fileContent));
                //Add for call {{ include('othertpl.twig') }}
        $fileContent = preg_replace(
            '/{{\s*include\s*\(\s*\'([^\']+)\'\s*\)\s*}}/i',
            '<?php include $this->__template(\'\1\'); ?>',
            $fileContent
        );
        $fileContent = preg_replace_callback(
            '/{{\s*(.+?)\s*(?:\|\s*(\S+)\s*)?}}/is',
            [$this, '__parseecho'],
            $fileContent
        );
        $fileContent = preg_replace_callback(
            '/{%\s*elseif\s+(.+?)\s*%}/is',
            [$this, '__parseelseif'],
            $fileContent
        );
        $fileContent = preg_replace('/{%\s*else\s*%}/is', '<?php } else { ?>', $fileContent);
        for ($i = 0; $i < 5; ++$i) {
            $fileContent = preg_replace_callback(
                '/{%\s*for\s+(\S+)\s*,\s*(\S+)\s+in\s+(\S+)\s*%}(.+?){%\s*endfor\s*%}/is',
                [$this, '__parseloopkeyval'],
                $fileContent
            );
            $fileContent = preg_replace_callback(
                '/{%\s*for\s+(\S+)\s+in\s+(\S+)\s*%}(.+?){%\s*endfor\s*%}/is',
                [$this, '__parseloop'],
                $fileContent
            );
            $fileContent = preg_replace_callback(
                '/{%\s*if\s+(.+?)\s*%}(.+?){%\s*endif\s*%}/is',
                [$this, '__parseif'],
                $fileContent
            );
        }
        $fileContent = preg_replace_callback(
            '/{%\s*(.+?)\s*%}/is',
            [$this, '__parsestmt'],
            $fileContent
        );
        if (file_put_contents($cFile, $fileContent) === false) {
            throw new \Exception('Can\'t write parsed template into file "'.$cFile.'"', 500);
        }
    }

    private function __replace($string)
    {
        return str_replace('\"', '"', $string);
    }

    private function __template($tFile)
    {
        $path = $this->getSettings()->getValue('path');
        $dir_template = $path['template'];
        $dir_compiled = $path['compiled'];
        $pattern = '/\.[\w]+$/';
        if (preg_match($pattern, $tFile)) {
            $tFileN = preg_replace($pattern, '', $tFile);
            $tFile = $dir_template . DIRECTORY_SEPARATOR . $tFile;
        } else {
            $tFileN = $tFile;
            $tFile = $dir_template . DIRECTORY_SEPARATOR . $tFileN . '.html';
        }
        $cFile = $dir_compiled . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $tFileN) . '.twig.php';
        if (false === file_exists($tFile)) {
            throw new \Exception('Template file "'.$tFile.'" not found', 404);
        }
        if (false === file_exists($cFile) || @filemtime($tFile) > @filemtime($cFile)) {
            $this->__parse($tFile, $cFile);
        }
        return $cFile;
    }
}

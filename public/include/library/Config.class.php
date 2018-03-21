<?php
class Config
{
    private static $mInstance = [];
    /**
     * Instance of this singleton class
     *
     * @return ConfigArray
     */
    public static function Instance($type = 'php')
    {
        $config_file = $type;
        $type = substr($config_file, -3); //only support php
        switch ($type) {
            case 'php':
                return self::LoadFromPhp($config_file);
        }
        return null;
    }

    private static function LoadFromPhp($config_file = 'php')
    {
        if ($config_file == 'php') {
            $config_file = DIR_CONFIGURE . '/' . 'system.php';
        } elseif (0 !== strpos($config_file, '/')) {
            $config_file = DIR_CONFIGURE . '/' . $config_file;
        }

        if (isset(self::$mInstance[$config_file])) {
            return self::$mInstance[$config_file];
        }

        if (file_exists($config_file)) {
            global $INI;
            require($config_file);
            self::$mInstance[$config_file] = $instance = $INI;
            return $instance;
        }
        return null;
    }

    public static function MergeINI($ini1, $ini2)
    {
        settype($ini1, 'array');
        settype($ini2, 'array');
        return array_merge($ini1, $ini2);
    }

    private static function ToArray($i)
    {
        if (is_object($i)) {
            $c = $i->children();
            if (!count($c)) {
                $o = (string)$i;
            } else {
                $o = new stdClass();
                foreach ($c as $k => $v) {
                    if (isset($o->$k)) {
                        if (!is_array($o->$k)) {
                            $o->$k = [$o->$k];
                        }
                        $o->{$k}[] = self::_dump($v);
                    } else {
                        $o->$k = self::_dump($v);
                    }
                }
            }
            $i = $o;
        } elseif (is_array($i)) {
            foreach ($i as $k => $v) {
                $i[$k] = self::_dump($v);
            }
        }
        return $i;
    }
}

<?php
class Session
{
    public static function Set($name, $v)
    {
        global $app;
        $app->getSession()->set($name, $v);
    }

    public static function Get($name, $once = false)
    {
        global $app;
        $value = $app->getSession()->get($name);
        if ($once && $app->getSession()->has($name)) {
            $app->getSession()->remove($name);
        }
        return $value;
    }
}

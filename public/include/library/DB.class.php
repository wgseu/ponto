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
class DB
{
    private static $mInstance = null;
    public static $pdo = null;

    protected static $transactionCounter = 0;

    function __construct($config)
    {
        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $user = $config['user'];
        $pass = $config['pass'];
        $values = [];
        $values[] = $driver . ':' . ($driver == 'sqlite' ? $config['name'] : 'dbname=' . $config['name']);
        if ($driver != 'sqlite') {
            $values[] =  'host=' . $config['host'];
            $values[] =  'port=' . $config['port'];
            $values[] = 'charset=utf8';
        }
        $dsn = implode(';', $values);
        $_pdo = new PDO($dsn, $user, $pass);
        $_pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo = new FluentPDO($_pdo);
    }

    public static function BeginTransaction()
    {
        self::$transactionCounter++;
        if (self::$transactionCounter == 1) {
            return self::$pdo->getPdo()->beginTransaction();
        }
        return self::$transactionCounter == 1;
    }

    public static function Commit()
    {
        if (self::$transactionCounter <= 0) {
            throw new \Exception('No transaction active');
        }
        self::$transactionCounter--;
        if (self::$transactionCounter == 0) {
            return self::$pdo->getPdo()->commit();
        }
        return self::$transactionCounter == 0;
    }

    public static function RollBack()
    {
        if (self::$transactionCounter <= 0) {
            throw new \Exception('No transaction active');
        }
        self::$transactionCounter--;
        if (self::$transactionCounter == 0) {
            return self::$pdo->getPdo()->rollBack();
        }
        return self::$transactionCounter == 0;
    }

    public static function &Instance($config)
    {
        if (self::$mInstance == null) {
            self::$mInstance = new self($config);
        }
        return self::$mInstance;
    }
}

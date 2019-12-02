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

namespace App\Util;

/**
 * Date time utils
 */
class Date
{
    /**
     * Total number of minutes in one day
     */
    public const MINUTES_PER_DAY = 1440;

    /**
     * Day of week
     */
    public const SUNDAY = 1;
    public const MONDAY = 2;
    public const TUESDAY = 3;
    public const FOURTH = 4;
    public const FIFTH = 5;
    public const FRIDAY = 6;
    public const SATURDAY = 7;

    /**
     * Get week offset in minutes started from sunday
     * @param  int $time get week offset for this time
     * @return int number of minutes from week begin
     */
    public static function weekOffset($time = null)
    {
        $time = $time ?: time();
        // week offset 1 to 7
        $week_day = (1 + date('w', $time));
        $today_sec = $time - strtotime('00:00', $time);
        $today_min = (int) ($today_sec / 60);
        return $week_day * self::MINUTES_PER_DAY + $today_min;
    }

    /**
     * Retorna dia da semana conforme parâmetro
     * @param int constante date
     * @return int
     */
    public static function make($day_week, $time)
    {
        return $day_week * self::MINUTES_PER_DAY + date('G', strtotime("$time")) * 60
        + date('i', strtotime("$time"));
    }
}

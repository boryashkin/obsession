<?php

namespace app\helpers;

use DateTime;

/**
 * Class DateHelper
 * @package app\helpers
 */
class DateHelper
{
    /**
     * Returns clone object, not modified passed param
     * @param DateTime $date
     * @return DateTime
     */
    public static function getStartOfWeek(DateTime $date)
    {
        $date = clone $date;
        if ($date->format('w') == 1) {
            return $date;
        }

        return $date->modify('previous monday')->setTime(23, 59, 59);
    }

    /**
     * Returns clone object, not modified passed param
     * @param DateTime $date
     * @return DateTime
     */
    public static function getEndOfWeek(DateTime $date)
    {
        $date = clone $date;
        if ($date->format('w') == 0) {
            return $date;
        }

        return $date->modify('next sunday')->setTime(0, 0, 0);
    }

    /**
     * Returns clone object, not modified passed param
     * @param DateTime $date
     * @return DateTime
     */
    public static function getStartOfMonth(DateTime $date)
    {
        $date = clone $date;

        return $date->modify('first day of')->setTime(0, 0, 0);
    }

    /**
     * Returns clone object, not modified passed param
     * @param DateTime $date
     * @return DateTime
     */
    public static function getEndOfMonth(DateTime $date)
    {
        $date = clone $date;

        return $date->modify('last day of')->setTime(23, 59, 59);
    }
}

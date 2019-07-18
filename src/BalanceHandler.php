<?php

namespace omny\yii2\payment\component;

use Exception;
use omny\yii2\payment\component\base\HandlerInterface;

/**
 * Class BalanceHandler
 * @package omny\yii2\payment\component\base
 */
class BalanceHandler implements HandlerInterface
{
    /**
     * @param float $currentValue
     * @param float $newValue
     * @return float
     * @throws Exception
     */
    public function reduce(float $currentValue, float $newValue): float
    {
        if ($currentValue < $newValue) {
            throw new Exception('The balance is small, not enough money.');
        }
        $newBalance = $currentValue - $newValue;

        return $newBalance;
    }

    /**
     * @param float $currentValue
     * @param float $newValue
     * @return float
     */
    public function increase(float $currentValue, float $newValue): float
    {
        return $currentValue + $newValue;
    }
}

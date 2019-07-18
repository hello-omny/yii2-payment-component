<?php

namespace omny\yii2\payment\component;

use Exception;
use omny\yii2\payment\component\base\HandlerInterface;

/**
 * Class HoldHandler
 * @package omny\yii2\payment\component\base
 */
class HoldHandler implements HandlerInterface
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
            throw new Exception('The hold is too small.');
        }

        $newHold = $currentValue - $newValue;

        return $newHold;
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

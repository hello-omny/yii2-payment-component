<?php

namespace omny\yii2\payment\component\base;

/**
 * Interface HandlerInterface
 * @package omny\yii2\payment\component\base
 */
interface HandlerInterface
{
    /**
     * @param float $currentValue
     * @param float $newValue
     * @return float
     */
    public function reduce(float $currentValue, float $newValue): float;

    /**
     * @param float $currentValue
     * @param float $newValue
     * @return float
     */
    public function increase(float $currentValue, float $newValue): float;
}

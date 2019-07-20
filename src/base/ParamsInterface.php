<?php

namespace omny\yii2\payment\component\base;

/**
 * Interface ParamsInterface
 * @package omny\yii2\payment\component\base
 */
interface ParamsInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     *
     */
    public function validate(): void;
}

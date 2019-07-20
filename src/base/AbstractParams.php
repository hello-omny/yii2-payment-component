<?php

namespace omny\yii2\payment\component\base;

use yii\base\BaseObject;
use yii\base\InvalidArgumentException;

/**
 * Class AbstractParams
 * @package omny\yii2\payment\component\base
 *
 * @property array $data
 */
abstract class AbstractParams extends BaseObject implements ParamsInterface
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(static::getData());
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }

    public function validate(): void
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $prop) {
            if ($prop->getValue($this) === null) {
                throw new InvalidArgumentException(sprintf(
                    'Error. Required param (%s) is empty.',
                    $prop->getName()
                ));
            }
        }
    }
}

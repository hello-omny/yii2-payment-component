<?php

namespace omny\yii2\payment\component\base;

use yii\db\ActiveRecord;

/**
 * Class BaseModel
 * @package omny\yii2\payment\component\base
 */
class BaseModel extends ActiveRecord
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->attributes);
    }
}

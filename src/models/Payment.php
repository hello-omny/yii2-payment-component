<?php

namespace omny\yii2\payment\component\models;

use omny\yii2\payment\component\base\BaseModel;

/**
 * Class Payment
 * @package omny\yii2\payment\component\models
 */
class Payment extends BaseModel
{
    const STATUS_NEW = 0;
    const STATUS_PERFORMED = 10;
    const STATUS_APPROVED = 20;
    const STATUS_DECLINE = 30;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'payment';
    }
}

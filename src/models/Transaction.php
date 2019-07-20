<?php

namespace omny\yii2\payment\component\models;

use omny\yii2\payment\component\base\BaseModel;

/**
 * Class Transaction
 * @package omny\yii2\payment\component\models
 *
 * @property float $value
 * @property int $target_id
 * @property int $type
 * @property int $user_id
 * @property string $created_at
 */
class Transaction extends BaseModel
{
    const TYPE_DEPOSIT = 0;
    const TYPE_INCOME = 10;
    const TYPE_PURCHASE = 20;
    const TYPE_HOLD = 30;
    const TYPE_REVERT_HOLD = 40;
    const TYPE_WITHDRAW = 50;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['value', 'string'],
            ['type', 'integer'],
            ['created_at', 'safe']
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'transaction';
    }
}

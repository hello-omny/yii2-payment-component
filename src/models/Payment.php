<?php

namespace omny\yii2\payment\component\models;

use omny\yii2\payment\component\base\BaseModel;

/**
 * Class Payment
 * @package omny\yii2\payment\component\models
 *
 * @property int $id
 * @property float $amount
 * @property int $status
 * @property int $user_id
 * @property string $created_at
 * @property string $made_at
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
    public static function tableName(): string
    {
        return 'payment';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['amount', 'number'],
            [['status', 'user_id'], 'integer'],
            [['created_at', 'made_at'], 'safe'],
        ];
    }
}

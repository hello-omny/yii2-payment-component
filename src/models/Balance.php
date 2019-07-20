<?php

namespace omny\yii2\payment\component\models;

use omny\yii2\payment\component\base\BaseModel;

/**
 * Class Balance
 * @package omny\yii2\payment\component\models
 *
 * @property string $id
 * @property float $value
 * @property int $user_id
 * @property string $updated_at
 */
class Balance extends BaseModel
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'balance';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['value', 'number'],
            ['user_id', 'integer'],
            ['updated_at', 'safe'],
        ];
    }
}

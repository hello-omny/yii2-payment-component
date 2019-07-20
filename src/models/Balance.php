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

    /**
     * @param int $userId
     * @return Balance
     */
    public static function getOneForUser(int $userId): self
    {
        $model = static::findOne(['user_id' => $userId]);

        if ($model === null) {
            $model = new static([
                'user_id' => $userId,
                'value' => 0,
            ]);
        }

        return $model;
    }
}

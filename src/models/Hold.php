<?php

namespace omny\yii2\payment\component\models;

/**
 * Class Hold
 * @package omny\yii2\payment\component\models
 *
 * @property string $id
 * @property float $value
 * @property int $user_id
 * @property string $updated_at
 */
class Hold extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'hold';
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

<?php

namespace omny\yii2\payment\component\params;

use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 * Class HoldParams
 * @package omny\yii2\payment\component\params
 */
class HoldParams extends BaseObject
{
    /** @var ActiveRecord */
    public $balance;
    /** @var ActiveRecord */
    public $hold;
    /** @var float */
    public $amount;
}

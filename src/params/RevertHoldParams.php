<?php

namespace omny\yii2\payment\component\params;

use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 * Class RevertHoldParams
 * @package omny\yii2\payment\component\params
 */
class RevertHoldParams extends BaseObject
{
    /** @var ActiveRecord */
    public $hold;
    /** @var ActiveRecord */
    public $balance;
    /** @var float */
    public $amount;
}

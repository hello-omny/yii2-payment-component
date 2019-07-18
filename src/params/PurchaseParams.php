<?php

namespace omny\yii2\payment\component\params;

use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 * Class PurchaseParams
 * @package omny\yii2\payment\component\params
 */
class PurchaseParams extends BaseObject
{
    /** @var ActiveRecord */
    public $hold;
    /** @var ActiveRecord */
    public $customerBalance;
    /** @var ActiveRecord */
    public $creatorBalance;
    /** @var ActiveRecord */
    public $systemBalance;
    /** @var float */
    public $amount;
    /** @var float */
    public $tax;
}

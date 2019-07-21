<?php

namespace omny\yii2\payment\component\params;

use omny\yii2\payment\component\base\AbstractParams;
use yii\db\ActiveRecord;

/**
 * Class PurchaseParams
 * @package omny\yii2\payment\component\params
 */
class PurchaseParams extends AbstractParams
{
    /** @var ActiveRecord */
    public $holdModel;
    /** @var float */
    public $holdValue;
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

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'holdValue' => $this->holdValue,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'holdModel' => (string)$this->holdModel,
            'customerBalance' => (string)$this->customerBalance,
            'creatorBalance' => (string)$this->creatorBalance,
            'systemBalance' => (string)$this->systemBalance,
        ];
    }
}

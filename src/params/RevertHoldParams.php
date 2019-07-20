<?php

namespace omny\yii2\payment\component\params;

use omny\yii2\payment\component\base\AbstractParams;
use yii\db\ActiveRecord;

/**
 * Class RevertHoldParams
 * @package omny\yii2\payment\component\params
 */
class RevertHoldParams extends AbstractParams
{
    /** @var ActiveRecord */
    public $hold;
    /** @var ActiveRecord */
    public $balance;
    /** @var float */
    public $amount;
    /** @var int */
    public $userId;

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'hold' => (string)$this->hold,
            'balance' => (string)$this->balance,
            'amount' => $this->amount,
            'userId' => $this->userId,
        ];
    }
}

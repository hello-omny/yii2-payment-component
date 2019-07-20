<?php

namespace omny\yii2\payment\component\params;

use omny\yii2\payment\component\base\AbstractParams;
use yii\db\ActiveRecord;

/**
 * Class HoldParams
 * @package omny\yii2\payment\component\params
 */
class HoldParams extends AbstractParams
{
    /** @var ActiveRecord */
    public $balance;
    /** @var ActiveRecord */
    public $hold;
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
            'balance' => (string)$this->balance,
            'hold' => (string)$this->hold,
            'amount' => $this->amount,
            'userId' => $this->userId,
        ];
    }
}

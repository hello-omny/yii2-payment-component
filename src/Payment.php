<?php

namespace omny\yii2\payment\component;

use omny\yii2\payment\component\base\AbstractPayment;

/**
 * Class Payment
 * @package omny\yii2\payment\component
 */
class Payment extends AbstractPayment
{
    /**
     * Payment constructor.
     * @param HoldHandler $holdHandler
     * @param BalanceHandler $balanceHandler
     */
    public function __construct(
        HoldHandler $holdHandler,
        BalanceHandler $balanceHandler
    )
    {
        $this->holdHandler = $holdHandler;
        $this->balanceHandler = $balanceHandler;
    }
}
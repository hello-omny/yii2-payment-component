<?php

namespace omny\yii2\payment\component;

use omny\yii2\payment\component\base\AbstractPayment;
use omny\yii2\payment\component\params\HoldParams;
use omny\yii2\payment\component\params\PurchaseParams;
use omny\yii2\payment\component\params\RevertHoldParams;

/**
 * Class Payment
 * @package omny\yii2\payment\component
 */
class Payment extends AbstractPayment
{
    /** @var TransactionHandler */
    protected $transactionHandler;

    /**
     * Payment constructor.
     * @param HoldHandler $holdHandler
     * @param BalanceHandler $balanceHandler
     * @param TransactionHandler $transactionHandler
     */
    public function __construct(
        HoldHandler $holdHandler,
        BalanceHandler $balanceHandler,
        TransactionHandler $transactionHandler
    )
    {
        parent::__construct($holdHandler, $balanceHandler);
        $this->transactionHandler = $transactionHandler;
    }

    /**
     * @param HoldParams $params
     * @return bool
     * @throws \Exception
     */
    public function hold(HoldParams $params): bool
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            parent::hold($params);
            $this->transactionHandler->create($params);

            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            $transaction->rollBack();

            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param RevertHoldParams $params
     * @return bool
     * @throws \Exception
     */
    public function revertHold(RevertHoldParams $params): bool
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            parent::revertHold($params);
            $this->transactionHandler->create($params);

            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            $transaction->rollBack();

            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param PurchaseParams $params
     * @return bool
     * @throws \Exception
     */
    public function purchase(PurchaseParams $params): bool
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            parent::purchase($params);
            $this->transactionHandler->create($params);

            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            $transaction->rollBack();

            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
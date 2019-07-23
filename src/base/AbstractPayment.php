<?php

namespace omny\yii2\payment\component\base;

use Exception;
use omny\yii2\payment\component\params\HoldParams;
use omny\yii2\payment\component\params\PurchaseParams;
use omny\yii2\payment\component\params\RevertHoldParams;
use omny\yii2\payment\component\params\WithdrawParams;

/**
 * Class AbstractPayment
 * @package omny\yii2\payment\component\base
 */
abstract class AbstractPayment
{
    /** @var string */
    public $balanceAttribute = 'value';
    /** @var string */
    public $holdAttribute = 'value';

    /** @var HandlerInterface */
    protected $holdHandler;
    /** @var HandlerInterface */
    protected $balanceHandler;

    /**
     * AbstractPayment constructor.
     * @param HandlerInterface $holdHandler
     * @param HandlerInterface $balanceHandler
     */
    public function __construct(
        HandlerInterface $holdHandler,
        HandlerInterface $balanceHandler
    )
    {
        $this->holdHandler = $holdHandler;
        $this->balanceHandler = $balanceHandler;
    }

    /**
     * @param HoldParams $params
     * @return bool
     * @throws Exception
     */
    public function hold(HoldParams $params): bool
    {
        $balanceAttribute = $this->balanceAttribute;
        $holdAttribute = $this->holdAttribute;

        $balance = $params->balance;
        $hold = $params->hold;
        $amount = $params->amount;

        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            $balance->$balanceAttribute = $this->balanceHandler
                ->reduce((float)$balance->$balanceAttribute, (float)$amount);
            $hold->$holdAttribute = $this->holdHandler
                ->increase((float)$hold->$holdAttribute, (float)$amount);

            if (!$balance->save()) {
                throw new Exception('Balance did not updated.');
            }

            if (!$hold->save()) {
                throw new Exception('Hold did not updated.');
            }

            $transaction->commit();
            return true;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param RevertHoldParams $params
     * @return bool
     * @throws Exception
     */
    public function revertHold(RevertHoldParams $params): bool
    {
        $holdAttribute = $this->holdAttribute;
        $balanceAttribute = $this->balanceAttribute;

        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            $params->hold->$holdAttribute = $this
                ->holdHandler
                ->reduce($params->hold->$holdAttribute, $params->amount);
            $params->balance->$balanceAttribute = $this
                ->balanceHandler
                ->increase($params->balance->$balanceAttribute, $params->amount);

            if ($params->balance->save() && $params->hold->save()) {
                $transaction->commit();
                return true;
            }

            throw new Exception('Error. Hold did not reverted.');
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new Exception(
                sprintf('Error. Hold did not reverted. %s', $exception->getMessage()),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param PurchaseParams $params
     * @return bool
     * @throws Exception
     */
    public function purchase(PurchaseParams $params): bool
    {
        $holdAttribute = $this->holdAttribute;
        $balanceAttribute = $this->balanceAttribute;

        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            $customerBalance = $params->customerBalance;
            $creatorBalance = $params->creatorBalance;
            $systemBalance = $params->systemBalance;
            $holdModel = $params->holdModel;

            $holdValue = $params->holdValue;
            if ($params->holdValue === null) {
                $holdValue = $params->amount + $params->tax;
            }

            // remove hold
            $holdModel->$holdAttribute = $this->holdHandler
                ->reduce($holdModel->$holdAttribute, $holdValue);

            // charge back hold
            $customerBalance->$balanceAttribute = $this->balanceHandler
                ->increase($customerBalance->$balanceAttribute, $holdValue);
            // reduce by real price
            $customerBalance->$balanceAttribute = $this->balanceHandler
                ->reduce($customerBalance->$balanceAttribute, $params->amount + $params->tax);

            // increase creator balance
            $creatorBalance->$balanceAttribute = $this->balanceHandler
                ->increase($creatorBalance->$balanceAttribute, $params->amount);

            // update system balance
            $systemBalance->$balanceAttribute = $this->balanceHandler
                ->increase($systemBalance->$balanceAttribute, $params->tax);

            if ($holdModel->save()
                && $customerBalance->save()
                && $creatorBalance->save()
                && $systemBalance->save()) {

                $transaction->commit();
                return true;
            }

            throw new Exception('Purchase failed.');
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new Exception(
                sprintf('Purchase failed. %s', $exception->getMessage()),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function withdraw(WithdrawParams $params): bool
    {
        throw new \Exception('no code yet');
    }
}

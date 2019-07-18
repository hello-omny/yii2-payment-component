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

        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            $params->balance->$balanceAttribute = $this
                ->balanceHandler
                ->reduce((float)$params->balance->$balanceAttribute, (float)$params->amount);
            $params->hold->$holdAttribute = $this
                ->holdHandler
                ->increase((float)$params->hold->$holdAttribute, (float)$params->amount);

            if ($params->balance->save() && $params->hold->save()) {
                $transaction->commit();
                return true;
            }

            throw new Exception('Hold and balance did not updated.');
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
            $params->hold->$holdAttribute = $this
                ->holdHandler
                ->reduce($params->hold->$holdAttribute, $params->amount + $params->tax);
            $params->customerBalance->$balanceAttribute = $this
                ->balanceHandler
                ->reduce($params->customerBalance->$balanceAttribute, $params->amount + $params->tax);
            $params->creatorBalance->$balanceAttribute = $this
                ->balanceHandler
                ->increase($params->creatorBalance->$balanceAttribute, $params->amount);
            $params->systemBalance->$balanceAttribute = $this
                ->balanceHandler
                ->increase($params->systemBalance->$balanceAttribute, $params->tax);

            if ($params->hold->save()
                && $params->customerBalance->save()
                && $params->creatorBalance->save()
                && $params->systemBalance->save()) {

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

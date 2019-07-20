<?php

namespace omny\yii2\payment\component;

use omny\yii2\payment\component\models\Transaction;
use omny\yii2\payment\component\params\HoldParams;
use omny\yii2\payment\component\base\ParamsInterface;
use omny\yii2\payment\component\params\PurchaseParams;
use omny\yii2\payment\component\params\RevertHoldParams;
use omny\yii2\payment\component\params\WithdrawParams;

/**
 * Class TransactionHandler
 * @package omny\yii2\payment\component
 */
class TransactionHandler
{
    /**
     * @param ParamsInterface $params
     * @return Transaction
     * @throws \Exception
     */
    public function create(ParamsInterface $params): Transaction
    {
        $paramsClass = get_class($params);

        switch ($paramsClass) {
            case HoldParams::class:
                $type = Transaction::TYPE_HOLD;
                break;
            case RevertHoldParams::class:
                $type = Transaction::TYPE_REVERT_HOLD;
                break;
            case PurchaseParams::class:
                $type = Transaction::TYPE_PURCHASE;
                break;
            case WithdrawParams::class:
                $type = Transaction::TYPE_WITHDRAW;
                break;
            default:
                $type = Transaction::TYPE_INCOME;
        }

        return $this->save($params, $type);
    }

    /**
     * @param string $data
     * @param int $type
     * @return Transaction
     * @throws \Exception
     */
    protected function save(string $data, int $type = Transaction::TYPE_INCOME): Transaction
    {
        $model = new Transaction([
            'value' => $data,
            'type' => $type,
        ]);

        if ($model->save()) {
            return $model;
        }

        throw new \Exception("Can't create transaction model.");
    }
}
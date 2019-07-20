<?php

namespace tests\_fixtures;

use omny\yii2\payment\component\models\Transaction;
use yii\test\ActiveFixture;

/**
 * Class TransactionFixture
 * @package tests\_fixtures
 */
class TransactionFixture extends ActiveFixture
{
    /** @var string */
    public $modelClass = Transaction::class;
}

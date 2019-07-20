<?php

namespace tests\_fixtures;

use omny\yii2\payment\component\models\Payment;
use yii\test\ActiveFixture;

/**
 * Class PaymentFixture
 * @package tests\_fixtures
 */
class PaymentFixture extends ActiveFixture
{
    /** @var string */
    public $modelClass = Payment::class;
}

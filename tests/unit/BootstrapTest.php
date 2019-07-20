<?php

namespace src\tests;

use omny\yii2\payment\component\BootstrapPayment;
use omny\yii2\payment\component\Payment;
use tests\TestCase;
use yii\base\UnknownPropertyException;
use yii\web\Application;

class BootstrapTest extends TestCase
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testPaymentWebAppOk()
    {
        $appConfig = [
            'bootstrap' => [
                BootstrapPayment::class
            ],
        ];
        $this->mockWebApplication($appConfig);

        $this->assertInstanceOf(Payment::class, \Yii::$app->payment);
    }

    public function testPaymentConsoleAppOk()
    {
        $appConfig = [
            'bootstrap' => [
                BootstrapPayment::class
            ],
        ];
        $this->mockApplication($appConfig);

        $this->expectException(UnknownPropertyException::class);
        \Yii::$app->payment;
    }
}
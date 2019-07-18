<?php namespace src\tests;

use omny\yii2\payment\component\models\Balance;
use omny\yii2\payment\component\models\Hold;
use omny\yii2\payment\component\params\HoldParams;
use omny\yii2\payment\component\params\PurchaseParams;
use omny\yii2\payment\component\params\RevertHoldParams;
use omny\yii2\payment\component\params\WithdrawParams;
use omny\yii2\payment\component\Payment;
use tests\_fixtures\BalanceFixture;
use tests\_fixtures\HoldFixture;

class PaymentTest extends \Codeception\Test\Unit
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;
    /** @var Payment */
    protected $payment;

    protected function _before()
    {
        $this->payment = \Yii::$container->get(Payment::class);

        $this->tester->haveFixtures([
            'hold' => [
                'class' => HoldFixture::class,
                'dataFile' => codecept_data_dir() . 'hold.php',
            ],
            'balance' => [
                'class' => BalanceFixture::class,
                'dataFile' => codecept_data_dir() . 'balance.php',
            ]
        ]);
    }

    protected function _after()
    {
    }

    /**
     * @throws \Exception
     */
    public function testHoldOk()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $balance = Balance::findOne(['user_id' => 1,]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $balance->value);

        $holdParams = new HoldParams([
            'hold' => $hold,
            'balance' => $balance,
            'amount' => 100,
        ]);

        $result = $this->payment->hold($holdParams);
        $this->assertEquals(true, $result);
        $this->assertEquals(400, $hold->value);
        $this->assertEquals(100, $balance->value);
    }

    /**
     * @throws \Exception
     */
    public function testHoldErrorNotEnoughMoney()
    {
        $hold = Hold::findOne(['user_id' => 2]);
        $balance = Balance::findOne(['user_id' => 2]);

        $this->assertEquals(0, $hold->value);
        $this->assertEquals(50, $balance->value);

        $holdParams = new HoldParams([
            'hold' => $hold,
            'balance' => $balance,
            'amount' => 100
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The balance is small, not enough money.');

        $this->payment->hold($holdParams);
        $this->assertEquals(0, $hold->value);
        $this->assertEquals(50, $balance->value);
    }

    public function testRevertHoldOk()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $balance = Balance::findOne(['user_id' => 1,]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $balance->value);

        $revertHoldParams = new RevertHoldParams([
            'hold' => $hold,
            'balance' => $balance,
            'amount' => 50
        ]);

        $result = $this->payment->revertHold($revertHoldParams);
        $this->assertEquals(true, $result);
        $this->assertEquals(250, $balance->value);
        $this->assertEquals(250, $hold->value);
    }

    public function testRevertHoldFail()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $balance = Balance::findOne(['user_id' => 1,]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $balance->value);

        $revertHoldParams = new RevertHoldParams([
            'hold' => $hold,
            'balance' => $balance,
            'amount' => 500
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error. Hold did not reverted. The hold is too small');

        $this->payment->revertHold($revertHoldParams);
        $this->assertEquals(250, $balance->value);
        $this->assertEquals(250, $hold->value);
    }

    /**
     * @throws \Exception
     */
    public function testPurchaseOk()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $customerBalance = Balance::findOne(['user_id' => 1,]);
        $creatorBalance = Balance::findOne(['user_id' => 2]);
        $systemBalance = Balance::findOne(['user_id' => 100]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $customerBalance->value);
        $this->assertEquals(50, $creatorBalance->value);
        $this->assertEquals(0, $systemBalance->value);

        $purchaseParams = new PurchaseParams([
            'hold' => $hold,
            'customerBalance' => $customerBalance,
            'creatorBalance' => $creatorBalance,
            'systemBalance' => $systemBalance,
            'amount' => 100,
            'tax' => 10,
        ]);

        $result = $this->payment->purchase($purchaseParams);
        $this->assertEquals(true, $result);
        $this->assertEquals(190, $hold->value);
        $this->assertEquals(90, $customerBalance->value);
        $this->assertEquals(150, $creatorBalance->value);
        $this->assertEquals(10, $systemBalance->value);
    }

    public function testPurchaseFailNotEnoughMoney()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $customerBalance = Balance::findOne(['user_id' => 1,]);
        $creatorBalance = Balance::findOne(['user_id' => 2]);
        $systemBalance = Balance::findOne(['user_id' => 100]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $customerBalance->value);
        $this->assertEquals(50, $creatorBalance->value);
        $this->assertEquals(0, $systemBalance->value);

        $purchaseParams = new PurchaseParams([
            'hold' => $hold,
            'customerBalance' => $customerBalance,
            'creatorBalance' => $creatorBalance,
            'systemBalance' => $systemBalance,
            'amount' => 250,
            'tax' => 10,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Purchase failed. The balance is small, not enough money.');
        $this->payment->purchase($purchaseParams);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $customerBalance->value);
        $this->assertEquals(50, $creatorBalance->value);
        $this->assertEquals(0, $systemBalance->value);
    }

    public function testPurchaseFailHoldSmall()
    {
        $hold = Hold::findOne(['user_id' => 1]);
        $customerBalance = Balance::findOne(['user_id' => 1,]);
        $creatorBalance = Balance::findOne(['user_id' => 2]);
        $systemBalance = Balance::findOne(['user_id' => 100]);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $customerBalance->value);
        $this->assertEquals(50, $creatorBalance->value);
        $this->assertEquals(0, $systemBalance->value);

        $purchaseParams = new PurchaseParams([
            'hold' => $hold,
            'customerBalance' => $customerBalance,
            'creatorBalance' => $creatorBalance,
            'systemBalance' => $systemBalance,
            'amount' => 300,
            'tax' => 10,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Purchase failed. The hold is too small.');
        $this->payment->purchase($purchaseParams);

        $this->assertEquals(300, $hold->value);
        $this->assertEquals(200, $customerBalance->value);
        $this->assertEquals(50, $creatorBalance->value);
        $this->assertEquals(0, $systemBalance->value);
    }

    public function testWithdrawOk()
    {
        $this->payment->withdraw(new WithdrawParams());
    }

    public function testWithdrawFail()
    {
        $this->payment->withdraw(new WithdrawParams());
    }
}
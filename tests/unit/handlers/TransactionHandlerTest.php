<?php namespace src\tests\handlers;

use omny\yii2\payment\component\models\Balance;
use omny\yii2\payment\component\models\Hold;
use omny\yii2\payment\component\models\Transaction;
use omny\yii2\payment\component\params\HoldParams;
use omny\yii2\payment\component\params\PurchaseParams;
use omny\yii2\payment\component\params\RevertHoldParams;
use omny\yii2\payment\component\params\WithdrawParams;
use omny\yii2\payment\component\TransactionHandler;
use tests\_fixtures\BalanceFixture;
use tests\_fixtures\HoldFixture;
use tests\_fixtures\PaymentFixture;
use tests\_fixtures\TransactionFixture;

class TransactionHandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;

    /** @var TransactionHandler */
    private $handler;

    protected function _before()
    {
        $this->handler = \Yii::$container->get(TransactionHandler::class);

        $this->tester->haveFixtures([
            'hold' => [
                'class' => HoldFixture::class,
                'dataFile' => codecept_data_dir() . 'hold.php',
            ],
            'balance' => [
                'class' => BalanceFixture::class,
                'dataFile' => codecept_data_dir() . 'balance.php',
            ],
            'transaction' => [
                'class' => TransactionFixture::class,
                'dataFile' => codecept_data_dir() . 'transaction.php',
            ],
            'payment' => [
                'class' => PaymentFixture::class,
                'dataFile' => codecept_data_dir() . 'payment.php',
            ],
        ]);
    }

    protected function _after()
    {
    }

    /**
     * @throws \Exception
     */
    public function testPurchaseParamsSaveOk()
    {
        $userId = 1;
        $hold = Hold::findOne(['user_id' => $userId]);
        $params = new PurchaseParams([
            'amount' => 100,
            'hold' => $hold,
            'customerBalance' => Balance::findOne(['user_id' => $userId]),
            'creatorBalance' => Balance::findOne(['user_id' => 2]),
            'systemBalance' => Balance::findOne(['user_id' => 100]),
            'tax' => 10,
        ]);

        $transaction = $this->handler->create($params);
        $this->assertInstanceOf(Transaction::class, $transaction);

        $savedTransaction = Transaction::find()->one();
        $holdFromTransaction = json_decode($savedTransaction->value, true)['hold'];

        $this->assertEquals($holdFromTransaction, $hold);
    }

    public function testHoldParamsSaveOk()
    {
        $userId = 1;
        $hold = Hold::findOne(['user_id' => $userId]);
        $params = new HoldParams([
            'amount' => 100,
            'hold' => $hold,
            'balance' => Balance::findOne(['user_id' => $userId]),
            'userId' => 1,
        ]);

        $transaction = $this->handler->create($params);
        $this->assertInstanceOf(Transaction::class, $transaction);

        $savedTransaction = Transaction::find()->one();
        $holdFromTransaction = json_decode($savedTransaction->value, true)['hold'];

        $this->assertEquals($holdFromTransaction, $hold);
    }

    public function testRevertHoldParamsSaveOk()
    {
        $userId = 1;
        $hold = Hold::findOne(['user_id' => $userId]);
        $params = new RevertHoldParams([
            'amount' => 100,
            'hold' => $hold,
            'balance' => Balance::findOne(['user_id' => $userId]),
            'userId' => 1,
        ]);

        $transaction = $this->handler->create($params);
        $this->assertInstanceOf(Transaction::class, $transaction);

        $savedTransaction = Transaction::find()->one();
        $holdFromTransaction = json_decode($savedTransaction->value, true)['hold'];

        $this->assertEquals($holdFromTransaction, $hold);
    }

    public function testWithdrawParamsSaveOk()
    {
        $userId = 1;
        $hold = Hold::findOne(['user_id' => $userId]);
        $params = new WithdrawParams([
            'amount' => 100,
            'hold' => $hold,
            'balance' => Balance::findOne(['user_id' => $userId]),
            'userId' => 1,
        ]);

        $transaction = $this->handler->create($params);
        $this->assertInstanceOf(Transaction::class, $transaction);

        $savedTransaction = Transaction::find()->one();
        $holdFromTransaction = json_decode($savedTransaction->value, true)['hold'];

        $this->assertEquals($holdFromTransaction, $hold);
    }
}
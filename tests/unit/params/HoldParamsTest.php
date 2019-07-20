<?php

namespace src\tests\params;

use omny\yii2\payment\component\models\Balance;
use omny\yii2\payment\component\models\Hold;
use omny\yii2\payment\component\params\HoldParams;
use tests\_fixtures\BalanceFixture;
use tests\_fixtures\HoldFixture;
use tests\_fixtures\PaymentFixture;
use tests\_fixtures\TransactionFixture;
use yii\base\InvalidArgumentException;

class HoldParamsTest extends \Codeception\Test\Unit
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
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

    public function testGetData()
    {
        $valuesMap = [
            'balance' => Balance::findOne(['user_id' => 1]),
            'hold' => Hold::findOne(['user_id' => 1]),
            'amount' => 100,
            'userId' => 1
        ];
        $params = new HoldParams($valuesMap);
        $data = $params->getData();

        $this->assertIsArray($data);
        $this->assertEquals($valuesMap, $data);
    }

    public function testStringValue()
    {
        $valuesMap = [
            'balance' => (string)Balance::findOne(['user_id' => 2]),
            'hold' => (string)Hold::findOne(['user_id' => 2]),
            'amount' => 100,
            'userId' => 1
        ];
        $params = new HoldParams($valuesMap);
        $data = (string)$params;

        $this->assertIsString($data);
        $this->assertEquals(json_encode($valuesMap), $data);
    }

    public function testValidationOk()
    {
        $valuesMap = [
            'balance' => Balance::findOne(['user_id' => 1]),
            'hold' => Hold::findOne(['user_id' => 1]),
            'amount' => 100,
            'userId' => 1
        ];
        $params = new HoldParams($valuesMap);
        $params->validate();
    }

    public function testValidationFailed()
    {
        $valuesMap = [
            'balance' => Balance::findOne(['user_id' => 1]),
            'hold' => Hold::findOne(['user_id' => 1]),
            'userId' => 1
        ];
        $params = new HoldParams($valuesMap);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error. Required param (amount) is empty.');

        $params->validate();
    }
}
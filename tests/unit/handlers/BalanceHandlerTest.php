<?php

namespace src\tests\handlers;

use omny\yii2\payment\component\BalanceHandler;

class BalanceHandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;
    /** @var BalanceHandler */
    protected $handler;

    protected function _before()
    {
        $this->handler = new BalanceHandler();
    }

    protected function _after()
    {
    }

    // tests
    public function testReduceFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The balance is small, not enough money.');

        $this->handler->reduce(100.4, 100.5);
    }

    public function testReduceOk()
    {
        $result = $this->handler->reduce(20.5, 10.2);
        $this->assertEquals(10.3, $result);
    }

    public function testIncreaseOk()
    {
        $result = $this->handler->increase(10.3, 2.2);
        $this->assertEquals(12.5, $result);
    }
}
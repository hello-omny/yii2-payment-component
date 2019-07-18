<?php

namespace src\tests\handlers;

use omny\yii2\payment\component\HoldHandler;

class HoldHandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \src\tests\UnitTester
     */
    protected $tester;
    /** @var HoldHandler */
    protected $handler;
    
    protected function _before()
    {
        $this->handler = new HoldHandler();
    }

    protected function _after()
    {
    }

    public function testReduceFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The hold is too small');
        $this->handler->reduce(100.5, 300);
    }

    public function testReduceOk()
    {
        $resultOk = $this->handler->reduce(1000.5, 300);
        $this->assertEquals(700.5, $resultOk);
    }

    public function testIncreaseOk()
    {
        $result = $this->handler->increase(100, 100);
        $this->assertEquals(200, $result);
    }
}
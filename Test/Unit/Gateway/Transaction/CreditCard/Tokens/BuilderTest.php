<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Tokens;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens\Builder;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
    	// $this->builder = new Builder;
    }

    public function tearDown()
    {

    }

    public function testTest()
    {
    	$this->markTestIncomplete();
    	
        $expected = [
            $token1,
        ];

        $result = $this->builder->build();

        static::assertEquals($expected, $result);
    }
}

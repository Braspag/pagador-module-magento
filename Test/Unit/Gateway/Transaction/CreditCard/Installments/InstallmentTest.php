<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\Installment;

class InstallmentTest extends \PHPUnit_Framework_TestCase
{
	protected $installment;

    public function setUp()
    {
    	$this->installment = new Installment;
    }

    public function tearDown()
    {

    }

    public function testSetData()
    {
    	$this->installment->setId(1);
    	$this->installment->setLabel('Cusom Label');

    	static::assertEquals(1, $this->installment->getId());
    	static::assertEquals('Cusom Label', $this->installment->getLabel());
    }
}

<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfig;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Magento\Framework\App\State;

class InstallmentsConfigTest extends \PHPUnit\Framework\TestCase
{
    /** @var  InstallmentsConfig */
    private $config;
    private $contextMock;
    private $scopeConfigMock;
    private $stateMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->stateMock = $this->createMock(State::class);

        $this->config = new InstallmentsConfig(
            $this->contextMock,
            $this->contextMock,
            $this->scopeConfigMock,
            $this->stateMock,
            [
                'code' => 'payment_method_custom'
            ]
        );
    }

    public function testGetData()
    {
    	$this->scopeConfigMock->expects($this->at(0))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installments_number')
    	    ->will($this->returnValue(10));

    	$this->scopeConfigMock->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installments_is_with_interest')
    	    ->will($this->returnValue(1));

    	$this->scopeConfigMock->expects($this->at(2))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installment_min_amount')
    	    ->will($this->returnValue(30.00));

    	$this->scopeConfigMock->expects($this->at(3))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installments_interest_rate')
    	    ->will($this->returnValue(20));

    	$this->scopeConfigMock->expects($this->at(4))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installments_interest_by_issuer')
    	    ->will($this->returnValue(1));

    	$this->scopeConfigMock->expects($this->at(5))
    	    ->method('getValue')
    	    ->with('payment/payment_method_custom/installments_max_without_interest')
    	    ->will($this->returnValue(5));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('payment/payment_method_custom/installments_active')
            ->will($this->returnValue(1));

        $this->contextMock->expects($this->exactly(7))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals(10, $this->config->getInstallmentsNumber());
        static::assertTrue($this->config->isWithInterest());
        static::assertEquals(30.00, $this->config->getInstallmentMinAmount());
        static::assertEquals(0.2, $this->config->getInterestRate());
        static::assertTrue($this->config->isInterestByIssuer());
        static::assertEquals(5, $this->config->getinstallmentsMaxWithoutInterest());
		static::assertTrue($this->config->isActive());
    }
}

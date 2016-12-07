<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentFactoryInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface;
use Magento\Checkout\Model\Cart\CartInterface;

class Builder implements BuilderInterface
{
	protected $installments = [];

	protected $instalLmentfactory;

	protected $installmentsConfig;

	protected $installmentFactory;

	protected $cart;

	protected $grandTotal;

	protected $installmentsNumber;

	public function __construct(
		InstallmentFactoryInterface $instalLmentfactory,
		InstallmentsConfigInterface $installmentsConfig,
		CartInterface $cart
	) {
		$this->setInstalLmentfactory($instalLmentfactory);
		$this->setInstallmentsConfig($installmentsConfig);
		$this->setCart($cart);
	}

    public function build()
    {
    	for ($i = 1; $i < $this->getInstallmentsNumber(); $i++) {

            if (!$this->canProcessInstallment($i)) {
                break;
            }

            $installment = $this->getInstallmentFactory()->create($i, $this->getGrandTotal(), $this->getInstallmentsConfig());
            $this->addInstallment($installment);
    	}

    	return $this->installments;
    }

    protected function addInstallment(InstallmentInterface $installment)
    {
        $this->installments[] = $installment;

        return $this;
    }

    protected function canProcessInstallment($i)
    {
        $installmentAmount = $this->getGrandTotal() / $i;
        return !($i > 1 && $installmentAmount < $this->getInstallmentsConfig()->getInstallmentMinAmount());
    }

    protected function getInstallmentsNumber()
    {
    	if (!$this->installmentsNumber) {
    		$this->installmentsNumber = (int) $this->getInstallmentsConfig()->getInstallmentsNumber();
    		$this->installmentsNumber++;
    	}

    	return $this->installmentsNumber;
    }

    protected function getGrandTotal()
    {
    	if (!$this->grandTotal) {
    		$this->grandTotal = $this->getCart()->getQuote()->getGrandTotal();
    	}

    	return $this->grandTotal;
    }

    protected function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }

    protected function getInstallmentFactory()
    {
        return $this->installmentFactory;
    }

    protected function setInstallmentFactory(InstallmentFactoryInterface $installmentFactory)
    {
        $this->installmentFactory = $installmentFactory;

        return $this;
    }

    protected function getCart()
    {
        return $this->cart;
    }

    protected function setCart(CartInterface $cart)
    {
        $this->cart = $cart;

        return $this;
    }
}

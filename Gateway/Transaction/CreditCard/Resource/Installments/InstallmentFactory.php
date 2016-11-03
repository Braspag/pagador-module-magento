<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Pricing\Helper\Data;

class InstallmentFactory implements InstallmentFactoryInterface
{
	protected $objectManager;

	protected $pricingHelper;

	public function __construct(
		ObjectManagerInterface $objectManager,
		Data $pricingHelper
	) {
		$this->setObjectManager($objectManager);
		$this->setPricingHelper($pricingHelper);
	}

    public function create($index, $total, InstallmentsConfigInterface $installmentsConfig)
    {
        $installmentAmount = $total / $index;
        $interestMessage = ' without interest';

        if ($installmentsConfig->isInterestByIssuer() && ($index > $installmentsConfig->getinstallmentsMaxWithoutInterest())) {
            $installmentAmount = $this->calcPriceWithInterest($index, $total, $installmentsConfig->getInterestRate());
            $interestMessage = ' with interest*';
        }

        $installmentAmount = $this->getPricingHelper()->currency($installmentAmount, true, false);
        
    	$installment = $this->getNewInstallmentInstance();
    	$installment->setId($index);
    	$installment->setLabel("{$index}x {$installmentAmount}{$interestMessage}");

    	return $installment;
    }

    public function getNewInstallmentInstance()
    {
    	return $this->getObjectManager()->create('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\Installment');
    }

    protected function getObjectManager()
    {
        return $this->objectManager;
    }

    protected function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }

    public function getPricingHelper()
    {
        return $this->pricingHelper;
    }

    protected function setPricingHelper(Data $pricingHelper)
    {
        $this->pricingHelper = $pricingHelper;

        return $this;
    }

    protected function calcPriceWithInterest($i, $total, $interestRate)
    {
        $price = $total * $interestRate / (1 - (1 / pow((1 + $interestRate), $i)));
        return sprintf("%01.2f", $price);
    }
}

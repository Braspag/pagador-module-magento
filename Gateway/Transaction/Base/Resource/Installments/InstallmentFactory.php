<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Pricing\Helper\Data;

class InstallmentFactory implements InstallmentFactoryInterface
{
	protected $objectManager;

	protected $priceHelper;

    protected $installmentClass;

	public function __construct(
		ObjectManagerInterface $objectManager,
        Data $priceHelper,
        $installmentClass = 'Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment'
	) {
		$this->setObjectManager($objectManager);
        $this->setPriceHelper($priceHelper);
        $this->setInstallmentClass($installmentClass);
	}

    public function create($index, $total, InstallmentsConfigInterface $installmentsConfig)
    {
        $installmentAmount = $total / $index;
        $interest = false;

        if ($installmentsConfig->isInterestByIssuer() && ($index > $installmentsConfig->getinstallmentsMaxWithoutInterest())) {
            $installmentAmount = $this->calcPriceWithInterest($index, $total, $installmentsConfig->getInterestRate());
            $interest = true;
        }
       
    	$installment = $this->getNewInstallmentInstance();
    	$installment->setIndex($index);
        $installment->setPrice($installmentAmount);
    	$installment->setWithInterest($interest);

    	return $installment;
    }

    protected function calcPriceWithInterest($i, $total, $interestRate)
    {
        $price = $total * $interestRate / (1 - (1 / pow((1 + $interestRate), $i)));
        return sprintf("%01.2f", $price);
    }

    protected function getNewInstallmentInstance()
    {
        return $this->getObjectManager()->create($this->getInstallmentClass(), ['priceHelper' => $this->getPriceHelper()]);
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

    protected function getPriceHelper()
    {
        return $this->priceHelper;
    }

    protected function setPriceHelper(Data $priceHelper)
    {
        $this->priceHelper = $priceHelper;

        return $this;
    }

    protected function getInstallmentClass()
    {
        return $this->installmentClass;
    }

    protected function setInstallmentClass($installmentClass)
    {
        $this->installmentClass = $installmentClass;

        return $this;
    }
}

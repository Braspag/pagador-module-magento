<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class InstallmentsConfig implements InstallmentsConfigInterface
{
	protected $config;

	public function __construct(
		ScopeConfigInterface $config
	){
		$this->setConfig($config);
	}

	public function getInstallmentsNumber()
	{
		return $this->_getConfig('installments_number');
	}

	public function isWithInterest()
	{
		return (bool) $this->_getConfig('installments_is_with_interest');
	}

	public function getInstallmentMinAmount()
	{
		return $this->_getConfig('installment_min_amount');
	}

	public function getInterestRate()
	{
		return ((int) $this->_getConfig('installments_interest_rate')) / 100;
	}

	public function isInterestByIssuer()
	{
		return (bool) $this->_getConfig('installments_interest_by_issuer');
	}

	public function getinstallmentsMaxWithoutInterest()
	{
		return $this->_getConfig('installments_max_without_interest');	
	}

	protected function _getConfig($field)
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    public function getConfig()
    {
        return $this->config;
    }

    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }
}

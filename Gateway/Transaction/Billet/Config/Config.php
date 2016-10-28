<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime;

/**
 * Braspag Transaction Billet Config
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Config implements ConfigInterface
{
	protected $config;

	protected $date;

	public function __construct(
		ScopeConfigInterface $config,
		DateTime $date
	){
		$this->setConfig($config);
		$this->setDate($date);
	}

	public function getMerchantId()
	{
		return $this->_getConfig('merchant_id');
	}

	public function getMerchantKey()
	{
		return $this->_getConfig('merchant_key');
	}

	public function getPaymentDemonstrative()
	{
		return $this->_getConfig('demonstrative');
	}

	public function getPaymentInstructions()
	{
		return $this->_getConfig('instructions');
	}

	public function getPaymentAssignor()
	{
		return $this->_getConfig('assignor');
	}

	public function getExpirationDate()
	{
		return $this->getDate()->gmDate('Y-m-d', strtotime(sprintf('+%s day', (int) $this->_getConfig('expiration_days'))));
	}

	public function getPaymentProvider()
	{
		return $this->_getConfig('provider');
	}

	protected function _getConfig($field)
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_billet/' . $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ScopeConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    protected function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the value of date.
     *
     * @param mixed $date the date
     *
     * @return self
     */
    protected function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }
}

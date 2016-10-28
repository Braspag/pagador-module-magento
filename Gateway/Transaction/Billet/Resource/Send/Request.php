<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
/**
 * Braspag Transaction Billet Send Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Request implements BraspagMagentoRequestInterface, BraspaglibRequestInterface
{
	protected $orderAdapter;
	
	protected $config;

	public function __construct(
		ConfigInterface $config
	) { 
		$this->setConfig($config);
	}

	public function getMerchantId()
	{
		return $this->getConfig()->getMerchantId();
	}

	public function getMerchantKey()
	{
		return $this->getConfig()->getMerchantKey();
	}

	public function getMerchantOrderId()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

	public function getCustomerName()
	{
        return $this->getOrderAdapter()->getBillingAddress()->getFirstname() . ' ' . $this->getOrderAdapter()->getBillingAddress()->getLastname();
	}

	public function getPaymentAmount()
	{
		return str_replace('.', '', $this->getOrderAdapter()->getGrandTotalAmount());
	}

	public function getPaymentAddress()
	{
		$address = $this->getOrderAdapter()->getBillingAddress();

		return sprintf("%s %s %s - %s", $address->getStreetLine1(), $address->getStreetLine2(), $address->getCity(), $address->getPostcode());
	}

	public function getPaymentProvider()
	{
		return $this->getConfig()->getPaymentProvider();
	}

	public function getPaymentBoletoNumber()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

	public function getPaymentAssignor()
	{
		return $this->getConfig()->getPaymentAssignor();
	}

	public function getPaymentDemonstrative()
	{
		return $this->getConfig()->getPaymentDemonstrative();
	}

	public function getPaymentExpirationDate()
	{
		return $this->getConfig()->getExpirationDate();
	}

	public function getPaymentIdentification()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

	public function getPaymentInstructions()
	{
		return $this->getConfig()->getPaymentInstructions();
	}

    protected function getOrderAdapter()
    {
        return $this->order;
    }

    public function setOrderAdapter(OrderAdapterInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }
}

<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Model\Order;

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
	const BRASPAG_PAYMENT_TYPE = 'Boleto';

	protected $order;
	protected $paymentInfo;
	protected $config;

	public function __construct(
		ConfigInterface $config
	) { 
		$this->setConfig($config);
	}

    public function setPaymentDataObject(PaymentDataObjectInterface $paymentDataObject)
    {
    	$this->setOrder($paymentDataObject->getOrder());
    	$this->setPaymentInfo($paymentDataObject->getPayment());

    	return $this;
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
		return $this->getOrder()->getIncrementId();
	}

	public function getCustomerName()
	{
        return $this->getOrder()->getCustomerName();
	}

	public function getPaymentType()
	{
		return self::BRASPAG_PAYMENT_TYPE;
	}

	public function getPaymentAmount()
	{
		return str_replace('.', '', $this->getOrder()->getPayment()->getAmountAuthorized());
	}

	public function getPaymentAddress()
	{
		return trim(implode(PHP_EOL, $this->getOrder()->getBillingAddress()->getStreet()));
	}

	public function getPaymentProvider()
	{
		return $this->getPaymentInfo()->getData('billet_type');
	}

	public function getPaymentBoletoNumber()
	{
		return $this->getOrder()->getIncrementId();
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
		return date('Y-m-d', strtotime($this->getOrder()->getCreatedAt() . ' +' . (int) $this->getConfig()->getExpirationDays() . ' day'));
	}

	public function getPaymentIdentification()
	{
		return $this->getOrder()->getIncrementId();
	}

	public function getPaymentInstructions()
	{
		return $this->getConfig()->getPaymentInstructions();
	}

    protected function getOrder()
    {
        return $this->order;
    }

    protected function setOrder(Order $order)
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

    protected function getPaymentInfo()
    {
        return $this->paymentInfo;
    }

    protected function setPaymentInfo(InfoInterface $paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;

        return $this;
    }
}

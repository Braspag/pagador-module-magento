<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Model\Order;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Magento\Payment\Model\InfoInterface;

class Request implements RequestInterface
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

    public function setPayment(PaymentDataObjectInterface $paymentDataObject)
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

	}

	public function getPaymentAssignor()
	{
		return $this->getConfig()->getPaymentDemonstrative();
	}

	public function getPaymentDemonstrative()
	{
		return $this->getConfig()->getPaymentDemonstrative();
	}

	public function getPaymentExpirationDate()
	{

	}

	public function getPaymentIdentification()
	{

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

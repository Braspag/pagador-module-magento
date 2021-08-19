<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;

/**
 * Braspag Transaction CreditCard Capture Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
	protected $request;

	public function __construct(
		RequestInterface $request,
        RequestPaymentSplitLibInterface $requestPaymentSplit,
        ConfigInterface $config
	) {
        $this->setRequest($request);
        $this->setPaymentSplitRequest($requestPaymentSplit);
        $this->setConfig($config);
	}

    protected function setPaymentSplitRequest(RequestPaymentSplitLibInterface $requestPaymentSplit)
    {
        $this->requestPaymentSplit = $requestPaymentSplit;
        return $this;
    }

    protected function getRequestPaymentSplit()
    {
        return $this->requestPaymentSplit;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    protected function getConfig()
    {
        return $this->config;
    }

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();

        $paymentId = $paymentDataObject->getPayment()->getAdditionalInformation('payment_token');

        if (empty($paymentId)) {
            $paymentId = str_replace(['-capture', '-void', '-refund'], '', $paymentDataObject->getPayment()->getLastTransId());
        }

        if ($this->getConfig()->isPaymentSplitActive()) {
            $this->getRequestPaymentSplit()->setConfig($this->getConfig());
            $this->getRequestPaymentSplit()->setOrder($paymentDataObject->getPayment()->getOrder());
            $this->getRequest()->setPaymentSplitRequest($this->getRequestPaymentSplit());
        }

        $this->getRequest()->setPaymentId($paymentId);
        $this->getRequest()->setOrderAdapter($orderAdapter);

        if (isset($buildSubject['amount']) && !empty($buildSubject['amount'])) {
            $this->getRequest()->setCaptureAmount($buildSubject['amount']);
        }

        return $this->getRequest();
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }
}

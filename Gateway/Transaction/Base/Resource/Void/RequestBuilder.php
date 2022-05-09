<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Void;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;

/**
 * Braspag Transaction CreditCard Capture Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
	protected $request;

	public function __construct(
		RequestInterface $request,
        ConfigInterface $config
	) {
        $this->setRequest($request);
        $this->setConfig($config);
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

        $this->getRequest()->setPaymentId($paymentId);

        $this->getRequest()->setOrderAdapter($orderAdapter);

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

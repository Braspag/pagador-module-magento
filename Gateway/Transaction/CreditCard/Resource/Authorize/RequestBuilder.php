<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Data\Order\OrderAdapter;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\RequestFactory;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\QuoteFactory;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;

/**
 * Braspag Transaction Boleto Send Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
    protected $requestFactory;
    protected $requestAntiFraud;
    protected $requestAvs;
    protected $requestPaymentSplit;
    protected $orderRepository;
    protected $config;
    protected $quoteItemFactory;
    protected $quoteFactory;

    public function __construct(
        RequestFactory $requestFactory,
        RequestAntiFraudLibInterface $requestAntiFraud,
        RequestAvsLibInterface $requestAvs,
        RequestPaymentSplitLibInterface $requestPaymentSplit,
        ConfigInterface $config,
        QuoteFactory $quoteFactory,
        ItemFactory $quoteItemFactory
    )
    {
        $this->setRequestFactory($requestFactory);
        $this->setAntiFraudRequest($requestAntiFraud);
        $this->setAvsRequest($requestAvs);
        $this->setPaymentSplitRequest($requestPaymentSplit);
        $this->setConfig($config);
        $this->setQuoteFactory($quoteFactory);
        $this->setQuoteItemFactory($quoteItemFactory);
    }

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $request = $this->getRequestFactory()->create();

        $paymentDataObject = $buildSubject['payment'];
        $paymentData = $paymentDataObject->getPayment();

        /** @var OrderAdapter $orderAdapter */
        $orderAdapter = $paymentDataObject->getOrder();
        $quote = $this->getQuoteByOrderItem($orderAdapter);

        if ($this->getConfig()->hasAntiFraud()) {
            $this->getRequestAntiFraud()->setOrderAdapter($orderAdapter);
            $this->getRequestAntiFraud()->setPaymentData($paymentData);
            $request->setAntiFraudRequest($this->getRequestAntiFraud());
        }

        if ($this->getConfig()->hasAvs()) {
            $this->getRequestAvs()->setOrderAdapter($orderAdapter);
            $this->getRequestAvs()->setPaymentData($paymentData);
            $request->setAvsRequest($this->getRequestAvs());
        }

        if ($this->getConfig()->isPaymentSplitActive()
            && ($this->getConfig()->isAuthorizeAndCapture()
                && $this->getConfig()->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL
            )
        ) {
            $this->getRequestPaymentSplit()->setConfig($this->getConfig());
            $this->getRequestPaymentSplit()->setQuote($quote);
            $request->setPaymentSplitRequest($this->getRequestPaymentSplit());
        }

        $request->setOrderAdapter($orderAdapter);
        $request->setPaymentData($paymentData);
        $request->setQuote($quote);

        return $request;
    }

    protected function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    protected function getRequestFactory()
    {
        return $this->requestFactory;
    }

    protected function setAntiFraudRequest(RequestAntiFraudLibInterface $requestAntiFraud)
    {
        $this->requestAntiFraud = $requestAntiFraud;
        return $this;
    }

    protected function getRequestAntiFraud()
    {
        return $this->requestAntiFraud;
    }

    protected function setAvsRequest(RequestAvsLibInterface $requestAvs)
    {
        $this->requestAvs = $requestAvs;
        return $this;
    }

    protected function getRequestAvs()
    {
        return $this->requestAvs;
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

    /**
     * @return QuoteFactory
     */
    public function getQuoteFactory()
    {
        return $this->quoteFactory;
    }

    /**
     * @param QuoteFactory $quoteFactory
     */
    public function setQuoteFactory($quoteFactory)
    {
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @return mixed
     */
    public function getQuoteItemFactory()
    {
        return $this->quoteItemFactory;
    }

    /**
     * @param mixed $quoteItemFactory
     */
    public function setQuoteItemFactory($quoteItemFactory)
    {
        $this->quoteItemFactory = $quoteItemFactory;
    }

    /**
     * @param $orderAdapter
     * @return \Magento\Quote\Model\Quote
     * @deprecated
     */
    protected function getQuoteByOrderItem($orderAdapter)
    {
        $quoteItemId = $this->getQuoteIdByFirstItem($orderAdapter);

        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $this->getQuoteItemFactory()->create();

        $quoteItem->load($quoteItemId);
        $quoteId = $quoteItem->getQuoteId();
        $quote = $this->getQuoteFactory()->create()->load($quoteId);
        return $quote;
    }

    /**
     * @param $orderAdapter
     * @return mixed
     * @deprecated
     */
    protected function getQuoteIdByFirstItem($orderAdapter)
    {
        $items = $orderAdapter->getItems();
        $firstItem = array_pop($items);
        $quoteItemId = $firstItem->getQuoteItemId();
        return $quoteItemId;
    }

}

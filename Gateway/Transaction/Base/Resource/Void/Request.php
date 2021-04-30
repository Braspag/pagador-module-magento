<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Void;

use Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Void\RequestInterface as BraspagMagentoRequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;
use Webjump\BraspagPagador\Helper\GrandTotal\Pricing as GrandTotalPricingHelper;

/**
 * Capture Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Request implements BraspaglibRequestInterface, BraspagMagentoRequestInterface
{
    protected $config;

    protected $orderAdapter;

    protected $paymentId;

    protected $storeId;

    /**
     * @var GrandTotalPricingHelper
     */
    protected $grandTotalPricingHelper;

    public function __construct(
        ConfigInterface $config,
        GrandTotalPricingHelper $grandTotalPricingHelper
    ) {
        $this->setConfig($config);
        $this->grandTotalPricingHelper = $grandTotalPricingHelper;
    }

    public function getMerchantId()
    {
        $storeId = $this->getOrderAdapter()->getStoreId();

        return $this->getConfig()->getMerchantId($storeId);
    }

    public function getMerchantKey()
    {
        $storeId = $this->getOrderAdapter()->getStoreId();

        return $this->getConfig()->getMerchantKey($storeId);
    }

    public function isTestEnvironment()
    {
        return $this->getConfig()->getIsTestEnvironment();
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getRequestDataBody()
    {
        return [];
    }

    public function getAdditionalRequest()
    {
        $grandTotalAmount = $this->getOrderAdapter()->getGrandTotalAmount();
        $integerValue = $this->grandTotalPricingHelper->currency($grandTotalAmount);

    	return [
            'amount' => $integerValue
        ];
    }

    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
    }

    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;

        return $this;
    }

    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeId = null)
    {
        $this->storeId = $storeId;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->storeId;
    }
}

<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\RequestInterface as BraspagMagentoRequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;

/**
 * Capture Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Request implements BraspaglibRequestInterface, BraspagMagentoRequestInterface
{
    protected $config;

    protected $orderAdapter;

    protected $paymentId;

    public function __construct(
        ConfigInterface $config
    ) {
        $this->setConfig($config);
    }

    public function getMerchantId()
    {
        return $this->getConfig()->getConfig()->getValue(
            \Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $this->getOrderAdapter()->getStoreId()
        );
    }

    public function getMerchantKey()
    {
        return $this->getConfig()->getConfig()->getValue(
            \Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $this->getOrderAdapter()->getStoreId()
        );
    }

    public function isTestEnvironment()
    {
        return $this->getConfig()->getIsTestEnvironment();
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getAdditionalRequest()
    {
        $amount = $this->getOrderAdapter()->getGrandTotalAmount() * 100;
        $amount = str_replace('.', '', $amount);

    	return [
            'amount' => $amount 
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
}

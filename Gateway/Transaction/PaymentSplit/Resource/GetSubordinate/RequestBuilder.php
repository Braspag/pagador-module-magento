<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate\RequestFactory;

/**
 * Braspag Transaction Boleto Send Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

/**
 * Class RequestBuilder
 * @package Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate
 */
class RequestBuilder implements BuilderInterface
{
    protected $requestFactory;
    protected $config;
    protected $requestHttp;

    /**
     * RequestBuilder constructor.
     * @param \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate\RequestFactory $requestFactory
     * @param ConfigInterface $config
     * @param \Magento\Framework\App\Request\Http $requestHttp
     */
    public function __construct(
        RequestFactory $requestFactory,
        ConfigInterface $config,
        \Magento\Framework\App\RequestInterface $requestHttp
    ) {
        $this->setRequestFactory($requestFactory);
        $this->setConfig($config);
        $this->setRequestHttp($requestHttp);
    }

    /**
     * @param array $buildSubject
     * @return array|mixed
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['merchantId']) || empty($buildSubject['merchantId'])) {
            throw new \InvalidArgumentException(__('Subordinate MerchantId should be provided'));
        }

        $request = $this->getRequestFactory()->create();

        $request->setConfig($this->getConfig());

        $subordinateId = $buildSubject['subordinate'];
        $subordinateMerchantId = $buildSubject['merchantId'];

        $request->setSubordinateId($subordinateId);
        $request->setSubordinateMerchantId($subordinateMerchantId);

        return $request;
    }

    /**
     * @return mixed
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param mixed $requestFactory
     */
    public function setRequestFactory($requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param ConfigInterface $config
     * @return $this
     */
    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getRequestHttp()
    {
        return $this->requestHttp;
    }

    /**
     * @param mixed $requestHttp
     */
    public function setRequestHttp($requestHttp)
    {
        $this->requestHttp = $requestHttp;
    }
}

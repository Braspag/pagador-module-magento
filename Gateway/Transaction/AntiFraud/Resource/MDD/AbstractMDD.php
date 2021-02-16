<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\MDD;


use Detection\MobileDetect;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config\MDDConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

abstract class AbstractMDD
{
    private $config;
    private $orderAdapter;
    private $paymentData;
    private $orderCollectionFactory;
    private $mobileDetect;
    protected $helperData;

    public function __construct(
        MDDConfigInterface $config,
        OrderCollectionFactory $orderCollectionFactory,
        MobileDetect $mobileDetect,
        \Webjump\BraspagPagador\Helper\Data $helperData
    )
    {
        $this->setConfig($config);
        $this->setOrderCollectionFactory($orderCollectionFactory);
        $this->setMobileDetect($mobileDetect);
        $this->helperData = $helperData;
    }

    /**
     * @param MDDConfigInterface $config
     * @return $this
     */
    protected function setConfig(MDDConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return MDDConfigInterface
     */
    protected function getConfig()
    {
        return  $this->config;
    }

    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;
        return $this;
    }

    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
        return $this;
    }

    public function getOrderAdapter()
    {
        return $this-$this->orderAdapter;
    }

    /**
     * @return InfoInterface
     */
    public function getPaymentData()
    {
        return $this->paymentData;
    }

    protected function setOrderCollectionFactory(OrderCollectionFactory $orderCollectionFactory)
    {
        $this->orderCollectionFactory = $orderCollectionFactory;
        return $this;
    }

    /**
     * @return OrderCollectionFactory
     */
    public function getOrderCollectionFactory()
    {
        return $this->orderCollectionFactory;
    }

    /**
     * @deprecated
     * @todo use in class config
     * @return MobileDetect
     */
    public function getMobileDetect()
    {
        return $this->mobileDetect;
    }

    protected function setMobileDetect(MobileDetect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
        return $this;
    }

}

<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime;

class Context implements ContextInterface
{
    private $config;
    private $session;
    private $storeManager;
    private $dateTime;

    public function __construct(
        ScopeConfigInterface $config,
        SessionManagerInterface $session,
        StoreManagerInterface $storeManager,
        DateTime $dateTime
    )
    {
        $this->setConfig($config);
        $this->setSession($session);
        $this->setStoreManager($storeManager);
        $this->setDateTime($dateTime);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getSession()
    {
        return $this->session;

    }

    public function getStoreManager()
    {
        return $this->storeManager;
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    protected function setConfig(ScopeConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;
        return $this;
    }

    protected function setStoreManager(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

    protected function setDateTime(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }
}

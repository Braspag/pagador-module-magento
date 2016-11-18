<?php

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Model\CardTokenFactoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;

class CardTokenFactory implements CardTokenFactoryInterface
{
	protected $objectManager;

    protected $storeManager;

    protected $session;

	public function __construct(
		ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Session $session
	) {
		$this->setObjectManager($objectManager);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
	}

    public function create($alias, $token)
   	{
   		$cardToken = $this->getObjectManager()->create('Webjump\BraspagPagador\Model\CardToken');

        $cardToken->setAlias($alias);
        $cardToken->setToken($token);
        $cardToken->setCustomerId($this->getSession()->getCustomerId());
        $cardToken->setStoreId($this->getStoreManager()->getStore()->getId());

        return $cardToken;
   	}

    protected function getObjectManager()
    {
        return $this->objectManager;
    }

    protected function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }

    protected function getStoreManager()
    {
        return $this->storeManager;
    }

    protected function setStoreManager(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;

        return $this;
    }

    protected function getSession()
    {
        return $this->session;
    }

    protected function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }
}

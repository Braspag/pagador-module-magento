<?php

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Model\CardTokenFactoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;

/**
 * Card Token factory
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CardTokenFactory implements CardTokenFactoryInterface
{
	protected $objectManager;

    protected $storeManager;

    protected $session;

    protected $instanceName;

	public function __construct(
		ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Session $session,
        $instanceName = CardToken::class
	) {
		$this->setObjectManager($objectManager);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setInstanceName($instanceName);
	}

    public function create($alias, $token)
   	{
   		$cardToken = $this->getObjectManager()->create($this->getInstanceName());

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

    protected function setInstanceName($instanceName)
    {
        $this->instanceName = $instanceName;

        return $this;
    }

    protected function getInstanceName()
    {
        return $this->instanceName;
    }
}

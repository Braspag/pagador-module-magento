<?php

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Api\Data\CardTokenInterface;
use Webjump\BraspagPagador\Model\CardTokenFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Webjump\BraspagPagador\Model\ResourceModel\CardToken as CardTokenResourceModel;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;

/**
 * Card Token repository
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CardTokenRepository implements CardTokenRepositoryInterface
{
	protected $cardTokenFactory;

    protected $storeManager;

    protected $session;

    protected $resource;

    protected $instances = [];

	public function __construct(
		CardTokenFactory $cardTokenFactory,
        StoreManagerInterface $storeManager,
        Session $session,
        CardTokenResourceModel $resource

	) {
		$this->setCardTokenFactory($cardTokenFactory);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setResource($resource);
	}

	public function get($token, $forceReload = false)
	{
		if (!isset($this->instances[$token]) || $forceReload) {
			$cardToken = $this->getCardTokenFactory()->create();
			$cardToken->load($token, CardTokenInterface::TOKEN);

			if (!$cardTokenId = $cardToken->getId()) {
				return false;
			}

			$this->instances[$token] = $cardToken;
		}

		return $this->instances[$token];
	}

	public function create($alias, $token)
	{
   		$cardToken = $this->getCardTokenFactory()->create();

        $cardToken->setAlias($alias);
        $cardToken->setToken($token);
        $cardToken->setCustomerId($this->getSession()->getCustomerId());
        $cardToken->setStoreId($this->getStoreManager()->getStore()->getId());
        $cardToken->setActive(true);

        return $cardToken;
	}

	public function save(CardTokenInterface $cardToken)
	{
		try {
			$this->getResource()->save($cardToken);
		} catch (Exception $e) {
			throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save Card Token'));
		}

		unset($this->instances[$cardToken->getToken()]);

		return $this->get($cardToken->getToken());
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

    protected function getCardTokenFactory()
    {
        return $this->cardTokenFactory;
    }

    protected function setCardTokenFactory(CardTokenFactory $cardTokenFactory)
    {
        $this->cardTokenFactory = $cardTokenFactory;

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

    protected function getResource()
    {
        return $this->resource;
    }

    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }
}

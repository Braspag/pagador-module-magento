<?php

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Api\Data\CardTokenInterface;
use Webjump\BraspagPagador\Model\CardTokenFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Webjump\BraspagPagador\Model\ResourceModel\CardToken as CardTokenResourceModel;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Webjump\BraspagPagador\Model\ResourceModel\CardToken\Collection;

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

    protected $searchResults;

	public function __construct(
		CardTokenFactory $cardTokenFactory,
        StoreManagerInterface $storeManager,
        Session $session,
        CardTokenResourceModel $resource,
        SearchResultsInterface $searchResults
	) {
		$this->setCardTokenFactory($cardTokenFactory);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setResource($resource);
        $this->setSearchResults($searchResults);
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

	public function create($alias, $token, $provider, $brand)
	{
   		$cardToken = $this->getCardTokenFactory()->create();

        $cardToken->setAlias($alias);
        $cardToken->setToken($token);
        $cardToken->setProvider($provider);
        $cardToken->setBrand($brand);
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

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCardTokenFactory()->create()->getCollection();

        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        $searchResult = $this->getSearchResults();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    protected function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $collection->addFieldToFilter($filter->getField(), [$conditionType => $filter->getValue()]);            
        }

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

    protected function getSearchResults()
    {
        return $this->searchResults;
    }

    protected function setSearchResults($searchResults)
    {
        $this->searchResults = $searchResults;

        return $this;
    }
}

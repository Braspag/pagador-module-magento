<?php

namespace Webjump\BraspagPagador\Model;

use Braintree\Exception;
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
    /**
     * @var
     */
    protected $cardTokenFactory;

    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var
     */
    protected $session;

    /**
     * @var
     */
    protected $resource;

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var
     */
    protected $searchResults;

    /**
     * CardTokenRepository constructor.
     *
     * @param \Webjump\BraspagPagador\Model\CardTokenFactory $cardTokenFactory
     * @param StoreManagerInterface                          $storeManager
     * @param Session                                        $session
     * @param CardTokenResourceModel                         $resource
     * @param SearchResultsInterface                         $searchResults
     */
    public function __construct(
        CardTokenFactory $cardTokenFactory,
        StoreManagerInterface $storeManager,
        Session $session,
        CardTokenResourceModel $resource,
        SearchResultsInterface $searchResults
    )
    {
        $this->setCardTokenFactory($cardTokenFactory);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setResource($resource);
        $this->setSearchResults($searchResults);
    }

    /**
     * @param string $token
     * @param bool   $forceReload
     *
     * @return bool|mixed
     */
    public function get($token, $forceReload = false)
    {
        if (!isset($this->instances[$token]) || $forceReload) {
            $cardToken = $this->getCardTokenFactory()->create();
            $cardToken->load($token, CardTokenInterface::TOKEN);

            if (!$cardToken->getId()) {
                return false;
            }

            $this->instances[$token] = $cardToken;
        }

        return $this->instances[$token];
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $cardToken = $this->getCardTokenFactory()->create();

        $cardToken->setData($data);

        $cardToken->setCustomerId($this->getSession()->getCustomerId());
        $cardToken->setStoreId($this->getStoreManager()->getStore()->getId());
        $cardToken->setActive(true);

        return $cardToken;
    }

    /**
     * @param CardTokenInterface $cardToken
     *
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
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

    /**
     * @param $cardToken
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(CardTokenInterface  $cardToken)
    {
        try {
            $this->getResource()->delete($cardToken);
        } catch (Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__('Unable to delete Card Token'));
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCardTokenFactory()->create()->getCollection();

        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
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

    /**
     * @param FilterGroup $filterGroup
     * @param Collection  $collection
     *
     * @return $this
     */
    protected function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $collection->addFieldToFilter($filter->getField(), [$conditionType => $filter->getValue()]);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     *
     * @return $this
     */
    protected function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCardTokenFactory()
    {
        return $this->cardTokenFactory;
    }

    /**
     * @param \Webjump\BraspagPagador\Model\CardTokenFactory $cardTokenFactory
     *
     * @return $this
     */
    protected function setCardTokenFactory(CardTokenFactory $cardTokenFactory)
    {
        $this->cardTokenFactory = $cardTokenFactory;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @param StoreManagerInterface $storeManager
     *
     * @return $this
     */
    protected function setStoreManager(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getResource()
    {
        return $this->resource;
    }

    /**
     * @param $resource
     *
     * @return $this
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getSearchResults()
    {
        return $this->searchResults;
    }

    /**
     * @param $searchResults
     *
     * @return $this
     */
    protected function setSearchResults($searchResults)
    {
        $this->searchResults = $searchResults;

        return $this;
    }
}

<?php

namespace Webjump\BraspagPagador\Model;

use Braintree\Exception;
use Webjump\BraspagPagador\Api\Data\SplitItemInterface;
use Webjump\BraspagPagador\Model\Split\ItemFactory as SplitItemFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Webjump\BraspagPagador\Model\ResourceModel\Split\Item as SplitItemResourceModel;
use Webjump\BraspagPagador\Api\SplitItemRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Webjump\BraspagPagador\Model\ResourceModel\Split\Item\Collection;

/**
 * SplitItem repository
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SplitItemRepository implements SplitItemRepositoryInterface
{
    /**
     * @var
     */
    protected $splitItemFactory;

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
     * SplitItemRepository constructor.
     *
     * @param \Webjump\BraspagPagador\Model\Split\ItemFactory $splitItemFactory
     * @param StoreManagerInterface                          $storeManager
     * @param Session                                        $session
     * @param SplitItemResourceModel                         $resource
     * @param SearchResultsInterface                         $searchResults
     */
    public function __construct(
        SplitItemFactory $splitItemFactory,
        StoreManagerInterface $storeManager,
        Session $session,
        SplitItemResourceModel $resource,
        SearchResultsInterface $searchResults
    )
    {
        $this->setSplitItemFactory($splitItemFactory);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setResource($resource);
        $this->setSearchResults($searchResults);
    }

    /**
     * @param string $splitItemId
     * @param bool   $forceReload
     *
     * @return bool|mixed
     */
    public function get($splitItemId, $forceReload = false)
    {
        if (!isset($this->instances[$splitItemId]) || $forceReload) {
            $splitItem = $this->getSplitItemFactory()->create();
            $splitItem->load($splitItemId, SplitItemInterface::TOKEN);

            if (!$splitItem->getId()) {
                return false;
            }

            $this->instances[$splitItemId] = $splitItem;
        }

        return $this->instances[$splitItemId];
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create($data = [])
    {
        $splitItem = $this->getSplitItemFactory()->create();

        $splitItem->setData($data);

        $splitItem->setStoreId($this->getStoreManager()->getStore()->getId());

        return $splitItem;
    }

    /**
     * @param SplitItemInterface $splitItem
     *
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(SplitItemInterface $splitItem)
    {
        try {
            $this->getResource()->save($splitItem);
        } catch (Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save SplitItem'));
        }

        unset($this->instances[$splitItem->getId()]);

        return $this->get($splitItem->getId());
    }

    /**
     * @param $splitItem
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SplitItemInterface  $splitItem)
    {
        try {
            $this->getResource()->delete($splitItem);
        } catch (Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__('Unable to delete SplitItem'));
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getSplitItemFactory()->create()->getCollection();

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
    protected function getSplitItemFactory()
    {
        return $this->splitItemFactory;
    }

    /**
     * @param \Webjump\BraspagPagador\Model\Split\ItemFactory $splitItemFactory
     *
     * @return $this
     */
    protected function setSplitItemFactory(SplitItemFactory $splitItemFactory)
    {
        $this->splitItemFactory = $splitItemFactory;

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

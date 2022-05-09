<?php

namespace Webjump\BraspagPagador\Model;

use Braintree\Exception;
use Webjump\BraspagPagador\Api\Data\SplitInterface;
use Webjump\BraspagPagador\Model\SplitFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Webjump\BraspagPagador\Model\ResourceModel\Split as SplitResourceModel;
use Webjump\BraspagPagador\Api\SplitRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Webjump\BraspagPagador\Model\ResourceModel\Split\Collection;

/**
 * Split repository
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SplitRepository implements SplitRepositoryInterface
{
    /**
     * @var
     */
    protected $splitFactory;

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
     * SplitRepository constructor.
     *
     * @param \Webjump\BraspagPagador\Model\SplitFactory $splitFactory
     * @param StoreManagerInterface                          $storeManager
     * @param Session                                        $session
     * @param SplitResourceModel                         $resource
     * @param SearchResultsInterface                         $searchResults
     */
    public function __construct(
        SplitFactory $splitFactory,
        StoreManagerInterface $storeManager,
        Session $session,
        SplitResourceModel $resource,
        SearchResultsInterface $searchResults
    )
    {
        $this->setSplitFactory($splitFactory);
        $this->setStoreManager($storeManager);
        $this->setSession($session);
        $this->setResource($resource);
        $this->setSearchResults($searchResults);
    }

    /**
     * @param string $splitId
     * @param bool   $forceReload
     *
     * @return bool|mixed
     */
    public function get($splitId, $forceReload = false)
    {
        if (!isset($this->instances[$splitId]) || $forceReload) {
            $split = $this->getSplitFactory()->create();
            $split->load($splitId, SplitInterface::TOKEN);

            if (!$split->getId()) {
                return false;
            }

            $this->instances[$splitId] = $split;
        }

        return $this->instances[$splitId];
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create($data = [])
    {
        $split = $this->getSplitFactory()->create();

        $split->setData($data);

        $split->setStoreId($this->getStoreManager()->getStore()->getId());

        return $split;
    }

    /**
     * @param SplitInterface $split
     *
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(SplitInterface $split)
    {
        try {
            $this->getResource()->save($split);
        } catch (Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save Split'));
        }

        unset($this->instances[$split->getId()]);

        return $this->get($split->getId());
    }

    /**
     * @param $split
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SplitInterface  $split)
    {
        try {
            $this->getResource()->delete($split);
        } catch (Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__('Unable to delete Split'));
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getSplitFactory()->create()->getCollection();

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
    protected function getSplitFactory()
    {
        return $this->splitFactory;
    }

    /**
     * @param \Webjump\BraspagPagador\Model\SplitFactory $splitFactory
     *
     * @return $this
     */
    protected function setSplitFactory(SplitFactory $splitFactory)
    {
        $this->splitFactory = $splitFactory;

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

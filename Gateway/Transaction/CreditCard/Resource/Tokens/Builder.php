<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens;

use Magento\Store\Model\StoreManagerInterface;


/**
 * Class Builder
 * @package Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens
 */
class Builder implements BuilderInterface
{
    /**
     * @var
     */
    protected $searchCriteriaBuilder;

    /**
     * @var
     */
    protected $filterBuilder;

    /**
     * @var
     */
    protected $filterGroupBuilder;

    /**
     * @var
     */
    protected $cardTokenRepository;

    /**
     * @var
     */
    protected $customerSession;

    /**
     * @var
     */
    protected $storeManager;

    /**
     * Builder constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteriaBuilder             $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder                     $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder         $filterGroupBuilder
     * @param \Webjump\BraspagPagador\Api\CardTokenRepositoryInterface $cardTokenRepository
     * @param \Magento\Customer\Model\Session                          $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface               $storeManager
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Webjump\BraspagPagador\Api\CardTokenRepositoryInterface $cardTokenRepository,
        \Magento\Customer\Model\Session $customerSession,
        StoreManagerInterface $storeManager
    ) {
        $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
        $this->setFilterBuilder($filterBuilder);
        $this->setFilterGroupBuilder($filterGroupBuilder);
        $this->setCardTokenRepository($cardTokenRepository);
        $this->setStoreManager($storeManager);
        $this->setCustomerSession($customerSession);
    }

    /**
     * @return array
     */
    public function build()
    {
        if (!$this->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        $filterCustomerLogged = $this->getFilterBuilder()
            ->setField('customer_id')
            ->setValue($this->getCustomerSession()->getCustomerId())
            ->setConditionType('eq')
            ->create();

        $filterCurrentStore = $this->getFilterBuilder()->setField('store_id')
            ->setValue($this->getStoreManager()->getStore()->getId())
            ->setConditionType('eq')
            ->create();

        $filterActive = $this->getFilterBuilder()->setField('active')
            ->setValue(true)
            ->setConditionType('eq')
            ->create();

        $filterMethod = $this->getFilterBuilder()->setField('method')
            ->setValue('braspag_pagador_creditcard')
            ->setConditionType('eq')
            ->create();

        $filterGroup = $this->getFilterGroupBuilder()
            ->addFilter($filterCustomerLogged)
            ->addFilter($filterCurrentStore)
            ->addFilter($filterActive)
            ->addFilter($filterMethod)
            ->create();

        $criteria = $this->getSearchCriteriaBuilder()
            ->setFilterGroups([$filterGroup])
            ->setPageSize(10)
            ->create();

        $result = $this->getCardTokenRepository()->getList($criteria);

        return $result->getItems();
    }

    /**
     * @return mixed
     */
    protected function getFilterGroupBuilder()
    {
        return $this->filterGroupBuilder;
    }

    /**
     * @param $filterGroupBuilder
     *
     * @return $this
     */
    protected function setFilterGroupBuilder($filterGroupBuilder)
    {
        $this->filterGroupBuilder = $filterGroupBuilder;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * @param $searchCriteriaBuilder
     *
     * @return $this
     */
    protected function setSearchCriteriaBuilder($searchCriteriaBuilder)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getFilterBuilder()
    {
        return $this->filterBuilder;
    }

    /**
     * @param $filterBuilder
     *
     * @return $this
     */
    protected function setFilterBuilder($filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCardTokenRepository()
    {
        return $this->cardTokenRepository;
    }

    /**
     * @param $cardTokenRepository
     *
     * @return $this
     */
    protected function setCardTokenRepository($cardTokenRepository)
    {
        $this->cardTokenRepository = $cardTokenRepository;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * @param $customerSession
     *
     * @return $this
     */
    protected function setCustomerSession($customerSession)
    {
        $this->customerSession = $customerSession;

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
     * @param $storeManager
     *
     * @return $this
     */
    protected function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;

        return $this;
    }
}

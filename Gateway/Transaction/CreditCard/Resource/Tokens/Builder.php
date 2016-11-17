<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens;


class Builder implements BuilderInterface
{
	protected $searchCriteriaBuilder;

	protected $filterBuilder;

	protected $filterGroupBuilder;

	protected $cardTokenRepository;

	protected $customerSession;

	protected $storeManager;

	public function __construct(
	    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
	    \Magento\Framework\Api\FilterBuilder $filterBuilder,
	    \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
	    \Webjump\BraspagPagador\Api\CardTokenRepositoryInterface $cardTokenRepository,
	    \Magento\Customer\Model\Session $customerSession,
	    \Magento\Store\Model\StoreManagerInterface $storeManager
	) {
	    $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
	    $this->setFilterBuilder($filterBuilder);
	    $this->setFilterGroupBuilder($filterGroupBuilder);
	    $this->setCardTokenRepository($cardTokenRepository);
	    $this->setStoreManager($storeManager);
	    $this->setCustomerSession($customerSession);
	}

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

		$filterGroup = $this->getFilterGroupBuilder()
	        ->addFilter($filterCustomerLogged)
	        ->addFilter($filterCurrentStore)
	        ->addFilter($filterActive)
	        ->create();

		$criteria = $this->getSearchCriteriaBuilder()
		        ->setFilterGroups([$filterGroup])
		        ->setPageSize(10)
		        ->create();            
		
		$result = $this->getCardTokenRepository()->getList($criteria);

		return $result->getItems();
	}

    protected function getFilterGroupBuilder()
    {
        return $this->filterGroupBuilder;
    }

    protected function setFilterGroupBuilder($filterGroupBuilder)
    {
        $this->filterGroupBuilder = $filterGroupBuilder;

        return $this;
    }

    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    protected function setSearchCriteriaBuilder($searchCriteriaBuilder)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        return $this;
    }

    protected function getFilterBuilder()
    {
        return $this->filterBuilder;
    }

    protected function setFilterBuilder($filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;

        return $this;
    }

    protected function getCardTokenRepository()
    {
        return $this->cardTokenRepository;
    }

    protected function setCardTokenRepository($cardTokenRepository)
    {
        $this->cardTokenRepository = $cardTokenRepository;

        return $this;
    }

    protected function getCustomerSession()
    {
        return $this->customerSession;
    }

    protected function setCustomerSession($customerSession)
    {
        $this->customerSession = $customerSession;

        return $this;
    }

    protected function getStoreManager()
    {
        return $this->storeManager;
    }

    protected function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;

        return $this;
    }
}

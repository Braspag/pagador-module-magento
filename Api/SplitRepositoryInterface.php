<?php

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\SplitInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SplitRepositoryInterface
{
	/**
	 * get Card Split by token
	 *
	 * @param  string $token Split
	 *
	 * @return false|SplitInterface 	$oken	Card Split Instance
	 */
	public function get($token);

	/**
	 * Create a split Instance
	 *
	 * @param  array $data Card Split Data
	 *
	 * @return SplitInterface        Card Split Instance
	 */
	public function create($data);

	/**
	 * get List of Card Splits
	 *
	 * @param  SearchCriteriaInterface $searchCriteria Filter
	 *
	 * @return Magento\Framework\Api\SearchResultsInterface Result
	 */
	public function getList(SearchCriteriaInterface $searchCriteria);

	/**
	 * Card Split Save
	 *
	 * @param  SplitInterface $split Card Split To Save
	 *
	 * @return Exception|SplitInterface 	Card Split Save
	 */
	public function save(SplitInterface $split);

    /**
     * @param $split
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SplitInterface  $split);

}

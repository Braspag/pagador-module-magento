<?php

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\SplitItemInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SplitItemRepositoryInterface
{
	/**
	 * get Split Item by id
	 *
	 * @param  string $token Token
	 *
	 * @return false|SplitInterface 	$oken	Split Item Instance
	 */
	public function get($token);

	/**
	 * Create a split Instance
	 *
	 * @param  array $data Split Item Data
	 *
	 * @return SplitInterface        Split Item Instance
	 */
	public function create($data);

	/**
	 * get List of Split Items
	 *
	 * @param  SearchCriteriaInterface $searchCriteria Filter
	 *
	 * @return Magento\Framework\Api\SearchResultsInterface Result
	 */
	public function getList(SearchCriteriaInterface $searchCriteria);

	/**
	 * Split Item Save
	 *
	 * @param  SplitInterface $split Split Item To Save
	 *
	 * @return Exception|SplitInterface 	Split Item Save
	 */
	public function save(SplitItemInterface $split);

    /**
     * @param $split
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SplitItemInterface  $split);

}

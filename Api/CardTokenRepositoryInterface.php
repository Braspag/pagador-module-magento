<?php

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\CardTokenInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CardTokenRepositoryInterface
{
	/**
	 * get Card Token by token
	 *
	 * @param  string $token Token
	 *
	 * @return false|CardTokenInterface 	$oken	Card Token Instance
	 */
	public function get($token);

	/**
	 * Create a cardToken Instance
	 *
	 * @param  array $data Card Token Data
	 *
	 * @return CardTokenInterface        Card Token Instance
	 */
	public function create($data);

	/**
	 * get List of Card Tokens
	 *
	 * @param  SearchCriteriaInterface $searchCriteria Filter
	 *
	 * @return Magento\Framework\Api\SearchResultsInterface Result
	 */
	public function getList(SearchCriteriaInterface $searchCriteria);

	/**
	 * Card Token Save
	 *
	 * @param  CardTokenInterface $cardToken Card Token To Save
	 *
	 * @return Exception|CardTokenInterface 	Card Token Save
	 */
	public function save(CardTokenInterface $cardToken);

    /**
     * @param $cardToken
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(CardTokenInterface  $cardToken);

}

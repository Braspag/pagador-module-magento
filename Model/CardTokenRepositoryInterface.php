<?php

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

interface CardTokenRepositoryInterface
{
	public function get($token);

	public function create($alias, $token);

	public function save(CardTokenInterface $cardToken);
}

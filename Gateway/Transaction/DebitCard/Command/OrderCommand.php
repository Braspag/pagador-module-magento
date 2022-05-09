<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface;

/**
 * Braspag Transaction DebitCard Order Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class OrderCommand extends AbstractApiCommand
{
	protected function sendRequest($request)
	{
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Debitcard Order Request Lib object should be provided');
        }

		return $this->getApi()->sendDebit($request);
	}
}

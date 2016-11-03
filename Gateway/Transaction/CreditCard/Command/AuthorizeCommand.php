<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface;

/**
 * Braspag Transaction Credit Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class AuthorizeCommand extends AbstractApiCommand
{
	protected function sendRequest($request)
	{
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Billet Send Request Lib object should be provided');
        }

		return $this->getApi()->sendCreditCard($request);
	}
}

<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\Lock\RequestInterface;

/**
 * Braspag Transaction Credit Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class LockCommand extends AbstractApiCommand
{
	protected function sendRequest($request)
	{
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Boleto Split Transaction Post Send Request Lib object should be provided');
        }

        try {
            return $this->getApi()->sendSplitPaymentLock($request);
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            throw new \Magento\Sales\Exception\CouldNotInvoiceException(
                __('Braspag communication error. Error code: ' . $e->getCode())
            );
        }
	}
}

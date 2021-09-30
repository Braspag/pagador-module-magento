<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\CreateSubordinate\RequestInterface;

/**
 * Braspag Transaction Credit Create Subordinate Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CreateSubordinateCommand extends AbstractApiCommand
{
    /**
     * @param $request
     * @return mixed
     * @throws \Magento\Sales\Exception\CouldNotInvoiceException
     */
	protected function sendRequest($request)
	{
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Split Transaction Create Subordinate Send Request Lib object should be provided');
        }

        try {
            return $this->getApi()->sendSplitPaymentCreateSubordinate($request);
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            throw new \Magento\Sales\Exception\CouldNotInvoiceException(
                    __('Braspag communication error. Error code: ' . $e->getCode())
            );
        }
	}
}

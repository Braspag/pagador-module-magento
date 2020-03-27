<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\PaymentSplit\RequestInterface;

/**
 * Braspag Transaction Credit Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SplitPaymentTransactionPostCommand extends AbstractApiCommand
{
	protected function sendRequest($request)
	{
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Billet Send Request Lib object should be provided');
        }

        try {
            return $this->getApi()->sendCreditCardSplitPaymentTransactionPost($request);
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            throw new \Magento\Sales\Exception\CouldNotInvoiceException(
                __('Braspag communication error. Error code: ' . $e->getCode())
            );
        }
	}
}

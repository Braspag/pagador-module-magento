<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Command;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Braspag\Braspag\Pagador\Transaction\Api\Actions\RequestInterface;

/**
 * Braspag Transaction Credit Capture Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class VoidCommand extends AbstractApiCommand
{
    /**
     * @param $request
     * @return mixed
     * @throws \Magento\Sales\Exception\CouldNotInvoiceException Avoid the SDK throw to \Exception
     */
    protected function sendRequest($request)
    {
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Void Request Lib object should be provided');
        }

        try {
            return $this->getApi()->voidPayment($request);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Magento\Sales\Exception\CouldNotInvoiceException(
                __('Braspag communication error. Error code: ' . $e->getCode())
            );
        }
    }
}

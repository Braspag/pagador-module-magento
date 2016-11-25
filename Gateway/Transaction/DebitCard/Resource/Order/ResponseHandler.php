<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\ResponseInterface;


/**
 * Braspag Transaction DebitCard Order Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class ResponseHandler implements HandlerInterface
{
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        if (!isset($response['response']) || !$response['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag DebitCard Send Response Lib object should be provided');
        }

        /** @var ResponseInterface $response */
        $response = $response['response'];
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setAdditionalInformation('redirect_url', $response->getPaymentAuthenticationUrl());
        
        $payment->setIsTransactionClosed(false);

        return $this;
    }
}

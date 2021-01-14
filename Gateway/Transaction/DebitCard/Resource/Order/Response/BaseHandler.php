<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\DebitCard\Send\Response;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;

/**

 * Braspag Transaction DebitCard Order Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class BaseHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    /**
     * @param $payment
     * @param $response
     * @return $this
     */
    protected function _handle($payment, $response)
    {
        list($paymentProvider, $paymentBrand) = array_pad(explode('-', $payment->getCcType(), 2), 2, null);
        list($responseProvider, $responseBrand) = array_pad(explode('-', $response->getPaymentCardProvider(), 2), 2, null);
        $payment->setAdditionalInformation('send_provider', $paymentProvider);
        $payment->setAdditionalInformation('receive_provider', $responseProvider);

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setAdditionalInformation('redirect_url', $response->getPaymentAuthenticationUrl());
        $payment->setAdditionalInformation('braspag_payment_status', $response->getPaymentStatus());

        $payment->setIsTransactionClosed(false);

        return $response;
    }
}

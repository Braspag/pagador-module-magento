<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class BaseHandler extends AbstractHandler implements HandlerInterface
{
    protected function _handle($payment, $response)
    {
        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        if ($authenticationUrl = $response->getAuthenticationUrl()) {
            $payment->setAdditionalInformation('redirect_url', $authenticationUrl);
        }

        return $this;
    }
}

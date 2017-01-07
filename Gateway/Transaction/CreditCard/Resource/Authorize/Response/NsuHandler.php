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
class NsuHandler extends AbstractHandler implements HandlerInterface
{
    protected function _handle($payment, $response)
    {
        $payment->setAdditionalInformation('proof_of_sale', $response->getPaymentProofOfSale());
        $payment->setAdditionalInformation('payment_token', $response->getPaymentPaymentId());
		$payment->setAdditionalInformation('send_provider', $payment->getCcType());
		$payment->setAdditionalInformation('receive_provider', $response->getPaymentCardProvider());

        return $this;
    }
}

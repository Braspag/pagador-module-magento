<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface as AntiFraudResponseInterface;
use Webjump\BraspagPagador\Model\AntiFraud\Status\Config\ConfigInterface as StatusConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class StatusAntiFraudHandler extends AbstractHandler implements HandlerInterface
{
    const STATUS_ACCEPT = 1;
    const STATUS_REJECT = 2;
    const STATUS_REVIEW = 3;
    const STATUS_ABORTED = 4;
    const STATUS_ERROR = 5;

    protected function _handle($payment, $response)
    {
        $antiFraudResponse = $response->getPaymentFraudAnalysis();

        if (! ($antiFraudResponse instanceof AntiFraudResponseInterface)) {
            return $this;
        }

        switch ($antiFraudResponse->getStatus()) {
            case self::STATUS_REJECT:
                $payment->setIsFraudDetected(true);
                break;
            case self::STATUS_REVIEW :
            case self::STATUS_ABORTED :
            case self::STATUS_ERROR :
                $payment->setIsTransactionPending(true);
                break;
        }

        return $this;
    }
}

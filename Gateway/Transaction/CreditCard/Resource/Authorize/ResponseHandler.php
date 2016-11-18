<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Model\CardTokenFactoryInterface;

/**
 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class ResponseHandler implements HandlerInterface
{
    protected $cardTokenFactory;

    public function __construct(
        CardTokenFactoryInterface $cardTokenFactory
    ) {
        $this->setCardTokenFactory($cardTokenFactory);
    }

    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        if (!isset($response['response']) || !$response['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag CreditCard Send Response Lib object should be provided');
        }

        /** @var ResponseInterface $response */
        $response = $response['response'];
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        $cardToken = $this->getCardTokenFactory()->create($payment->getCcNumberEnc(), $response->getPaymentCardToken());
        $cardToken->save();

        return $this;
    }

    protected function getCardTokenFactory()
    {
        return $this->cardTokenFactory;
    }

    protected function setCardTokenFactory(CardTokenFactoryInterface $cardTokenFactory)
    {
        $this->cardTokenFactory = $cardTokenFactory;

        return $this;
    }
}

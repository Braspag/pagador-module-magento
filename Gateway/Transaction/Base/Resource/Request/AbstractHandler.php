<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
abstract class AbstractHandler implements HandlerInterface
{
    protected $request;

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function handle(array $handlingSubject, array $request)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        if (!isset($request['request']) || !$request['request'] instanceof $this->request) {
            throw new \InvalidArgumentException('Braspag Card Send Request Lib object should be provided');
        }

        /** @var ResponseInterface $request */
        $request = $request['request'];
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $request = $this->_handle($payment, $request);

        return $request;
    }

    abstract protected function _handle($payment, $request);
}

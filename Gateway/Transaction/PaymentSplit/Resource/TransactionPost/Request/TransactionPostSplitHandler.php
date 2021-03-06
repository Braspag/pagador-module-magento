<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\TransactionPost\Request;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\TransactionPost\Request;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request\AbstractHandler;
use Braspag\BraspagPagador\Model\SplitManager;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class TransactionPostSplitHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * @var
     */
    protected $session;

    /**
     * @var
     */
    protected $splitManager;

    public function __construct(
        SplitManager $splitManager,
        Request $request,
        \Magento\Checkout\Model\Session $session
    ) {
        $this->setSplitManager($splitManager);
        $this->setRequest($request);
        $this->setSession($session);
    }

    /**
     * @return Braspag\BraspagPagador\Model\SplitManager
     */
    public function getSplitManager(): SplitManager
    {
        return $this->splitManager;
    }

    /**
     * @param Braspag\BraspagPagador\Model\SplitManager $splitManager
     */
    public function setSplitManager(SplitManager $splitManager)
    {
        $this->splitManager = $splitManager;
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @param array $handlingSubject
     * @param array $request
     * @return $this|void|AbstractHandler
     */
    public function handle(array $handlingSubject, array $request)
    {
        if (!isset($request['request']) || !$request['request'] instanceof $this->request) {
            throw new \InvalidArgumentException('Braspag Card Send Request Lib object should be provided');
        }

        $request = $request['request'];

        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof \Magento\Sales\Model\Order\Payment) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $payment = $handlingSubject['payment'];
        $request = $this->_handle($payment, $request);

        return $request;
    }

    /**
     * @param $payment
     * @param $request
     * @return $this
     */
    protected function _handle($payment, $request)
    {
        if (!$request) {
            return $this;
        }

        $request->prepareSplits();
        $splitData = $request->getSplits();

        return $request;
    }
}
<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\TransactionPost\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\PaymentSplit\Response;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Model\SplitDataAdapter;

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
    protected $splitManager;

    /**
     * @var
     */
    protected $splitAdapter;

    public function __construct(
        SplitManager $splitManager,
        Response $response,
        SplitDataAdapter $splitAdapter
    ) {
        $this->setSplitManager($splitManager);
        $this->setResponse($response);
        $this->setSplitAdapter($splitAdapter);
    }

    /**
     * @return Webjump\BraspagPagador\Model\SplitManager
     */
    public function getSplitManager(): SplitManager
    {
        return $this->splitManager;
    }

    /**
     * @param Webjump\BraspagPagador\Model\SplitManager $splitManager
     */
    public function setSplitManager(SplitManager $splitManager)
    {
        $this->splitManager = $splitManager;
    }

    /**
     * @return mixed
     */
    public function getObjectFactory()
    {
        return $this->objectFactory;
    }

    /**
     * @param mixed $objectFactory
     */
    public function setObjectFactory($objectFactory)
    {
        $this->objectFactory = $objectFactory;
    }

    /**
     * @return mixed
     */
    public function getSplitAdapter()
    {
        return $this->splitAdapter;
    }

    /**
     * @param mixed $splitAdapter
     */
    public function setSplitAdapter($splitAdapter)
    {
        $this->splitAdapter = $splitAdapter;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     * @return $this|void|AbstractHandler
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($response['response']) || !$response['response'] instanceof $this->response) {
            throw new \InvalidArgumentException('Braspag CreditCard Send Response Lib object should be provided');
        }

        $response = $response['response'];

        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof \Magento\Sales\Model\Order\Payment) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $payment = $handlingSubject['payment'];
        $response = $this->_handle($payment, $response);

        return $response;
    }

    /**
     * @param $payment
     * @param $response
     * @return $this
     */
    protected function _handle($payment, $response)
    {
        if (!$response) {
            return $this;
        }

        $splitData = $this->getSplitAdapter()->adaptResponseData($response->getSplits(), null, 'transaction_post');

        $this->getSplitManager()->createPaymentSplitByOrder($payment->getOrder(), $splitData);

        return $response;
    }
}

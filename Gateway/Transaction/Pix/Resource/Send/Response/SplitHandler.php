<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Braspag\Braspag\Pagador\Transaction\Resource\Pix\Send\Response;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;
use Braspag\BraspagPagador\Model\SplitManager;
use Braspag\BraspagPagador\Model\SplitDataAdapter;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SplitHandler extends AbstractHandler implements HandlerInterface
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
     * @param $payment
     * @param $response
     * @return $this
     */
    protected function _handle($payment, $response)
    {
        $paymentSplitResponse = $response->getPaymentSplitPayments();

        if (!$paymentSplitResponse) {
            return $this;
        }

        $dataSplitPayment = $this->getSplitAdapter()->adaptResponseData($paymentSplitResponse->getSplits(), null, 'authorize');

        $payment->setAdditionalInformation('split_payments', $dataSplitPayment->getData());

        return $response;
    }
}
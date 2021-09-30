<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate\Request;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate\Request;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\AbstractHandler;
use Webjump\BraspagPagador\Model\SplitManager;

/**

 * Braspag Transaction Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CreateSubordinateHandler extends AbstractHandler implements HandlerInterface
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
     * @return array|mixed|void|\Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\ResponseInterface|CreateSubordinateHandler
     */
    public function handle(array $handlingSubject, array $request)
    {
        if (!isset($request['request']) || !$request['request'] instanceof $this->request) {
            throw new \InvalidArgumentException('Braspag Card Send Request Lib object should be provided');
        }

        $request = $request['request'];

        if (!isset($handlingSubject['subordinate'])) {
            throw new \InvalidArgumentException('Subordinate data object should be provided');
        }

        $request = $this->_handle($handlingSubject, $request);

        return $request;
    }

    /**
     * @param $handlingSubject
     * @param $request
     * @return $this
     */
    protected function _handle($handlingSubject, $request)
    {
        if (!$request) {
            return $this;
        }

        return $request;
    }
}

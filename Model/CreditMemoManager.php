<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Service\CreditmemoService;

class CreditMemoManager
{
    /**
     * @var \Magento\Sales\Model\Order\CreditmemoFactory
     */
    protected $creditmemoFactory;

    /**
     * @var \Magento\Sales\Model\Service\CreditmemoService
     */
    protected $creditmemoService;

    public function __construct(
        CreditmemoFactory $creditmemoFactory,
        CreditmemoService $creditmemoService
    ){
        $this->setCreditmemoFactory($creditmemoFactory);
        $this->setCreditmemoService($creditmemoService);
    }

    /**
     * @return mixed
     */
    public function getCreditmemoFactory()
    {
        return $this->creditmemoFactory;
    }

    /**
     * @param mixed $creditmemoFactory
     *
     * @return self
     */
    public function setCreditmemoFactory($creditmemoFactory)
    {
        $this->creditmemoFactory = $creditmemoFactory;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditmemoService()
    {
        return $this->creditmemoService;
    }

    /**
     * @param mixed $creditmemoService
     *
     * @return self
     */
    public function setCreditmemoService($creditmemoService)
    {
        $this->creditmemoService = $creditmemoService;

        return $this;
    }

    /**
     * @param $order
     * @return bool
     */
    public function createCreditMemo($order)
    {
        $creditmemo = $this->getCreditmemoFactory()->createByOrder($order);
        $this->getCreditmemoService()->refund($creditmemo, true);

        return true;
    }
}

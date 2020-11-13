<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\Items;


use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Checkout\Model\Session;

class RequestFactory
{
    protected $objectManager;
    protected $class;
    protected $session;

    /**
     * RequestFactory constructor.
     * @param ObjectManagerInterface $objectManager
     * @param string $class
     * @param SessionManagerInterface $session
     */
    public function __construct(ObjectManagerInterface $objectManager, $class = Request::class, SessionManagerInterface $session)
    {
        $this->setObjectManager($objectManager);
        $this->setClass($class);
        $this->setSession($session);
    }

    public function create(OrderItemInterface $orderItem)
    {
        return $this->getObjectManager()->create(
            $this->getClass(),
            [
                'itemAdapter' => $orderItem,
                'session'=> $this->getSession()
            ]
        );
    }

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionManagerInterface $session
     */
    protected function setSession($session)
    {
        $this->session = $session;
    }
}

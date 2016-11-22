<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Items;


use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class RequestFactory
{
    private $objectManager;
    private $class;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string $class
     */
    public function __construct(ObjectManagerInterface $objectManager, $class = Request::class)
    {
        $this->setObjectManager($objectManager);
        $this->setClass($class);
    }

    public function create(OrderItemInterface $orderItem)
    {
        return $this->getObjectManager()->create($this->getClass(), ['itemAdapter' => $orderItem]);
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
}

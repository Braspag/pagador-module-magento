<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Model\Payment\Info;


use Webjump\BraspagPagador\Api\Factories\FactoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\ObjectManagerInterface;

class BilletFactory implements FactoryInterface
{
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param OrderInterface $order
     * @return Billet
     */
    public function create(OrderInterface $order)
    {
        return $this->getObjectManager()->create(Billet::class, ['order' => $order]);
    }

    /**
     * @return ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        return $this->objectManager;
    }
}

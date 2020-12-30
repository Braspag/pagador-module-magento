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

use Magento\Sales\Api\Data\OrderInterface;

class BoletoFactory implements BoletoFactoryInterface
{
    /**
     * @param OrderInterface $order
     * @return Boleto
     */
    public function create(OrderInterface $order)
    {
        return new Boleto($order);
    }
}

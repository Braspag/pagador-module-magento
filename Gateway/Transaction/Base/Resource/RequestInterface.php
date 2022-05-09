<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Model\InfoInterface;

/**
 * Braspag Transaction Base Request Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface RequestInterface
{
	public function setOrderAdapter(OrderAdapterInterface $order);

	public function setPaymentData(InfoInterface $payment);
}
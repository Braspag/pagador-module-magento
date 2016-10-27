<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

/**
 * Braspag Transaction Billet Send Request Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface RequestInterface
{
	public function setPaymentDataObject(PaymentDataObjectInterface $paymentDataObject);
}
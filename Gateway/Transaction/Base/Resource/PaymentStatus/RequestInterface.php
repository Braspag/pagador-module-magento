<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus;

use Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface as BraspaglibRequestInterface;

interface RequestInterface extends BraspaglibRequestInterface
{
    /**
     * @param string $paymentId
     */
    public function setPaymentId(string $paymentId);

    /**
     * @return string
     */
    public function getPaymentId() : string;

    /**
     * @param integer $storeId
     */
    public function setStoreId($storeId = null);

    /**
     * @return mixed
     */
    public function getStoreId();
}
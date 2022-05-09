<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config;

class Request extends Config implements RequestInterface
{
    /**
     * @var string
     */
    protected $paymentId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var string
     */
    protected $additionalRequest;

    public function isTestEnvironment()
    {
        return $this->getIsTestEnvironment();
    }

    /**
     * @inheritDoc
     */
    public function setPaymentId(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @inheritDoc
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeId = null)
    {
        $this->storeId = $storeId;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param string $additionalRequest
     */
    public function setAdditionalRequest(string $additionalRequest)
    {
        $this->additionalRequest = $additionalRequest;
    }

    /**
     * @return string
     */
    public function getAdditionalRequest()
    {
        return $this->additionalRequest;
    }

    /**
     * @return array
     */
    public function getRequestDataBody()
    {
        return [];
    }
}
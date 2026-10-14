<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Init;

class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $orderNumber;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $amount;

    /**
     * @param string $orderNumber
     * @param string $currency
     * @param string $amount
     */
    public function __construct($orderNumber, $currency, $amount)
    {
        $this->orderNumber = $orderNumber;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'orderNumber' => $this->orderNumber,
            'currency'    => $this->currency,
            'amount'      => $this->amount,
        ];
    }
}

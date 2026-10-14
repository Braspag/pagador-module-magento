<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate;

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
    private $totalAmount;

    /**
     * @var array
     */
    private $card;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @param string $orderNumber
     * @param string $currency Alphabetic ISO 4217 (BRL, USD, …) — VALIDATE uses alpha
     * @param string $totalAmount Minor units as string
     * @param array  $card { cardnumber, expirationmonth, expirationyear }
     * @param string $transactionId
     */
    public function __construct($orderNumber, $currency, $totalAmount, array $card, $transactionId)
    {
        $this->orderNumber = $orderNumber;
        $this->currency = $currency;
        $this->totalAmount = $totalAmount;
        $this->card = $card;
        $this->transactionId = $transactionId;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'ordernumber'   => $this->orderNumber,
            'currency'      => $this->currency,
            'totalamount'   => $this->totalAmount,
            'card'          => $this->card,
            'transactionId' => $this->transactionId,
        ];
    }
}

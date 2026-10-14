<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth;

/**
 * Immutable value object holding the AUTH request payload plus the Basic Auth
 * header credential (already Base64-encoded).
 */
class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $basicAuthCredential;

    /**
     * @var string
     */
    private $establishmentCode;

    /**
     * @var string
     */
    private $merchantName;

    /**
     * @var string
     */
    private $mcc;

    /**
     * @param string $basicAuthCredential
     * @param string $establishmentCode
     * @param string $merchantName
     * @param string $mcc
     */
    public function __construct(
        $basicAuthCredential,
        $establishmentCode,
        $merchantName,
        $mcc
    ) {
        $this->basicAuthCredential = $basicAuthCredential;
        $this->establishmentCode = $establishmentCode;
        $this->merchantName = $merchantName;
        $this->mcc = $mcc;
    }

    /**
     * {@inheritdoc}
     */
    public function getBasicAuthCredential()
    {
        return $this->basicAuthCredential;
    }

    /**
     * {@inheritdoc}
     */
    public function getEstablishmentCode()
    {
        return $this->establishmentCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * {@inheritdoc}
     */
    public function getMcc()
    {
        return $this->mcc;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'EstablishmentCode' => $this->establishmentCode,
            'MerchantName'      => $this->merchantName,
            'MCC'               => $this->mcc,
        ];
    }
}

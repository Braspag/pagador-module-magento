<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth;

use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

/**
 * Builds AUTH requests off admin configuration. All missing-field errors
 * surface with a translatable phrase pointing the admin to the exact section
 * where the field lives.
 */
class Builder implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $clientId          = $this->config->getClientId();
        $clientSecret      = $this->config->getClientSecret();
        $establishmentCode = $this->config->getEstablishmentCode();
        $merchantName      = $this->config->getMerchantName();
        $mcc               = $this->config->getMcc();

        if (!$clientId || !$clientSecret) {
            throw new LocalizedException(new Phrase(
                'MPI V3: Client ID and Client Secret must be configured under '
                . 'Braspag Pagador > Authentication 3DS 2.x (MPI V3) > Access Token Generation.'
            ));
        }

        if (!$establishmentCode || !$merchantName || !$mcc) {
            throw new LocalizedException(new Phrase(
                'MPI V3: Establishment Code, Merchant Name and MCC must be '
                . 'configured under Braspag Pagador > Global Settings.'
            ));
        }

        return new Request(
            base64_encode($clientId . ':' . $clientSecret),
            $establishmentCode,
            $merchantName,
            $mcc
        );
    }
}

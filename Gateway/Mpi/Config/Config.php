<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Reads admin configuration for MPI V3.
 *
 * All getters are store-scope aware. The Client Secret is decrypted here so
 * downstream consumers (commands) receive the plaintext ready to concatenate
 * for the AUTH Basic header.
 */
class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientId()
    {
        return $this->readTrimmed(self::XML_PATH_CLIENT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getClientSecret()
    {
        $raw = $this->readTrimmed(self::XML_PATH_CLIENT_SECRET);
        if ($raw === null || $raw === '') {
            return null;
        }

        // Only decrypt when the value has the Magento encryption prefix
        // (`<version>:<algo>:<...>`, e.g. `0:2:`, `0:3:`). Older installs
        // that saved the client secret in plaintext (before this module's
        // Encrypted backend model was in place) still carry the raw value —
        // running decrypt() on plaintext produces garbage bytes and makes
        // the AUTH Basic header invalid, which surfaces as HTTP 400
        // invalid_client from Cielo.
        if (preg_match('/^\d+:\d+:/', $raw)) {
            return $this->encryptor->decrypt($raw);
        }
        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function getEstablishmentCode()
    {
        return $this->readTrimmed(self::XML_PATH_ESTABLISHMENT_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantName()
    {
        return $this->readTrimmed(self::XML_PATH_MERCHANT_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getMcc()
    {
        return $this->readTrimmed(self::XML_PATH_MCC);
    }

    /**
     * {@inheritdoc}
     */
    public function isSandbox()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_IS_SANDBOX,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getApiBase()
    {
        return $this->isSandbox() ? self::API_BASE_SANDBOX : self::API_BASE_PRODUCTION;
    }

    /**
     * {@inheritdoc}
     */
    public function getScriptBase()
    {
        return $this->isSandbox() ? self::SCRIPT_BASE_SANDBOX : self::SCRIPT_BASE_PRODUCTION;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthEndpoint()
    {
        return $this->getApiBase() . self::AUTH_ENDPOINT_PATH;
    }

    /**
     * {@inheritdoc}
     */
    public function isDebugActive()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_DEBUG_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $path
     * @return string|null
     */
    private function readTrimmed($path)
    {
        $value = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
        if ($value === null) {
            return null;
        }
        $trimmed = trim((string) $value);
        return $trimmed === '' ? null : $trimmed;
    }
}

<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\CreditCard;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Resolves the current credit card provider for a given brand by reading the
 * live admin configuration (payment/braspag_pagador_creditcard/cctypes), which
 * holds a comma-separated list shaped like "Cielo30-Visa,Cielo30-Master,...".
 *
 * The DataAssignObserver uses this resolver when a customer pays with a saved
 * card (1-click). Before this resolver existed, the observer trusted the
 * `provider` field persisted on the braspag_card_token row at the time the
 * card was originally saved — which became stale whenever the merchant
 * switched acquirers in the admin. Reading the provider from the current
 * cctypes config makes the 1-click flow honor the latest admin choice
 * without needing a database migration on the saved tokens.
 */
class ProviderResolver
{
    const CONFIG_PATH_CCTYPES = 'payment/braspag_pagador_creditcard/cctypes';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns the provider configured for the given card brand in the current
     * cctypes admin selection, or null if the brand isn't enabled anymore.
     * Callers (e.g. the observer) treat a null result as "this saved card
     * can no longer be used" and surface a friendly error to the customer.
     *
     * Matching is case-insensitive on the brand portion so that token brands
     * stored as "Visa" still match cctypes entries like "Cielo30-VISA".
     *
     * @param string $brand e.g. "Visa", "Master", "Hipercard"
     * @return string|null Provider name (e.g. "Cielo30") or null on no match
     */
    public function getProviderForBrand($brand)
    {
        if (!is_string($brand) || $brand === '') {
            return null;
        }

        $cctypes = (string) $this->scopeConfig->getValue(
            self::CONFIG_PATH_CCTYPES,
            ScopeInterface::SCOPE_STORE
        );
        if ($cctypes === '') {
            return null;
        }

        $normalizedBrand = strtolower($brand);

        foreach (explode(',', $cctypes) as $entry) {
            $entry = trim($entry);
            if ($entry === '') {
                continue;
            }
            $parts = explode('-', $entry, 2);
            if (count($parts) !== 2) {
                continue;
            }
            list($provider, $entryBrand) = $parts;
            if (strtolower($entryBrand) === $normalizedBrand) {
                return $provider;
            }
        }

        return null;
    }
}

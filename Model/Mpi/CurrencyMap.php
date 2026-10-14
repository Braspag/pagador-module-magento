<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

/**
 * Currency code translation helper.
 *
 * The INIT endpoint expects the numeric ISO 4217 code as a string
 * (e.g. "986" for BRL), while ENROLL/VALIDATE and the underlying /v2/sales/
 * transaction use the alphabetic code ("BRL"). Both live in the same map here
 * so a single change updates every command.
 */
class CurrencyMap
{
    /**
     * Alphabetic → numeric map. Add codes as merchants require them.
     */
    const MAP = [
        'BRL' => '986',
        'USD' => '840',
        'EUR' => '978',
        'GBP' => '826',
        'ARS' => '032',
        'CLP' => '152',
        'COP' => '170',
        'MXN' => '484',
        'PEN' => '604',
        'UYU' => '858',
    ];

    /**
     * @param string $alphabetic ISO 4217 alpha-3 (e.g. "BRL")
     * @return string Numeric code as string (e.g. "986"); falls back to "986"
     *         when the currency is unknown, keeping the checkout functional
     *         in the same behavior V2 had.
     */
    public function toNumeric($alphabetic)
    {
        $code = strtoupper((string) $alphabetic);
        return self::MAP[$code] ?? '986';
    }
}

<?php

namespace Webjump\BraspagPagador\Model\Source;

/**
 * CC Types
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

class Cctype extends \Magento\Payment\Model\Source\Cctype
{
    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return [
            'Simulado',
            'SimuladoCielo',
            'Cielo-Visa',
            'Cielo-Master',
            'Cielo-Amex',
            'Cielo-Elo',
            'Cielo-Aura',
            'Cielo-Jcb',
            'Cielo-Diners',
            'Cielo-Discover',
            'Cielo30-Visa',
            'Cielo30-Master',
            'Cielo30-Amex',
            'Cielo30-Elo',
            'Cielo30-Aura',
            'Cielo30-Jcb',
            'Cielo30-Diners',
            'Cielo30-Discover',
            'Rede-Visa',
            'Rede-Master',
            'Rede-Hipercard',
            'Rede-Hiper',
            'Rede-Elo',
            'Rede-Diners',
            'Rede2-Visa',
            'Rede2-Master',
            'Rede2-Hipercard',
            'Rede2-Hiper',
            'Rede2-Elo',
            'Rede2-Diners',
            'RedeSitef-Visa',
            'RedeSitef-Master',
            'RedeSitef-Hipercard',
            'RedeSitef-Diners',
            'CieloSitef-Visa',
            'CieloSitef-Master',
            'CieloSitef-Amex',
            'CieloSitef-Elo',
            'CieloSitef-Aura',
            'CieloSitef-Jcb',
            'CieloSitef-Diners',
            'CieloSitef-Discover',
            'SantanderSitef-Visa',
            'SantanderSitef-Master',
        ];
    }
}

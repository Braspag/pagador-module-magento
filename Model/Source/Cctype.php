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
            'Cielo30-Hipercard',
            'Cielo30-Hiper',
            'Getnet-Visa',
            'Getnet-Master',
            'Getnet-Elo',
            'Getnet-Amex',
            'Getnet-Hipercard',
            'Rede-Visa',
            'Rede-Master',
            'Rede-Hipercard',
            'Rede-Hiper',
            'Rede-Diners',
            'Rede-Elo',
            'Rede-Amex',
            'Rede2-Visa',
            'Rede2-Master',
            'Rede2-Hipercard',
            'Rede2-Hiper',
            'Rede2-Diners',
            'Rede2-Elo',
            'Rede2-Amex',
            'GlobalPayments-Visa',
            'GlobalPayments-Master',
            'Stone-Visa',
            'Stone-Master',
            'Stone-Hipercard',
            'Stone-Elo',
            'Safra-Visa',
            'Safra-Master',
            'Safra-Hipercard',
            'Safra-Elo',
            'Safra-Amex',
            'FirstData-Visa',
            'FirstData-Master',
            'FirstData-Cabal',
            'FirstData-Elo',
            'FirstData-Hipercard',
            'FirstData-Amex'
        ];
    }
}

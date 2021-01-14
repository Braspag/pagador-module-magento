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

class Dctype extends \Magento\Payment\Model\Source\Cctype
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
            'Cielo30-Visa',
            'Cielo30-Master',
            'Cielo30-Elo',
            'Getnet-Visa',
            'Getnet-Master',
            'Rede2-Visa',
            'Rede2-Master',
            'FirstData-Visa',
            'FirstData-Master',
            'GlobalPayments-Visa',
            'GlobalPayments-Master'
        ];
    }
}

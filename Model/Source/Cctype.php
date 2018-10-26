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
			'Rede-Visa',
			'Rede-Master',
			'Rede-Hipercard',
			'Rede-Hiper',
            'Rede-Elo',
			'Rede-Diners',
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

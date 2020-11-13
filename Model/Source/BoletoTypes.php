<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Model\Source;

class BoletoTypes extends \Magento\Payment\Model\Source\Cctype
{
    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return [
            "Simulado",
            "BancoDoBrasil2",
            "BankofAmerica",
            "Bradesco2",
            "Braspag",
            "Caixa2",
            "Citibank2",
            "Itau2",
            "ItauShopline",
            "Santander2"
        ];
    }
}

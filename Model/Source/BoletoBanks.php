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

class BoletoBanks implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            "BancoDoBrasil" => 'BancoDoBrasil',
            "BankofAmerica" => 'BankofAmerica',
            "Bradesco" => 'Bradesco',
            "Caixa" => 'Caixa',
            "Citibank" => 'Citibank',
            "Itau" => 'Itau',
            "ItauShopline" => 'ItauShopline',
            "Santander" => 'Santander',
        ];
    }
}

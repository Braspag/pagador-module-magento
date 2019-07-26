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

class BilletTypes extends \Magento\Payment\Model\Source\Cctype
{
    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return [
            'Simulado',
            'Bradesco',
            'Bradesco2',
            'BancoDoBrasil',
            'BancoDoBrasil2',
            'CitiBank',
            'ItauShopline',
            'Itau2',
            'Brb',
            'Caixa',
            'Santander',
            'HSBC'
        ];
    }
}

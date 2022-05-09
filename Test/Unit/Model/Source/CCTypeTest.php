<?php

/**
 *
 * Boleto Types Test.php
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Test\Unit\Model\Source;

use Webjump\BraspagPagador\Model\Source\Cctype;

class CctypeTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        // mocks

        $this->model = $objectManager->getObject(
            Cctype::class,
            []
        );
    }

    /** @test */
    public function test()
    {
        // prepare the test

        $expected = [
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

        // perform the changes

        $result = $this->model->getAllowedTypes();

        // test the results

        static::assertEquals($expected, $result);
    }
}

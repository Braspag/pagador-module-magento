<?php

/**
 *
 * Billet Types Test.php
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

        // perform the changes

        $result = $this->model->getAllowedTypes();

        // test the results

        static::assertEquals($expected, $result);
    }
}

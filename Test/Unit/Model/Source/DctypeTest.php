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

use Webjump\BraspagPagador\Model\Source\Dctype;

class DctypeTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        // mocks

        $this->model = $objectManager->getObject(
            Dctype::class,
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
            'Cielo-Elo',
            'Cielo30-Visa',
            'Cielo30-Master',
            'Cielo30-Elo',
            'Rede-Visa',
            'Rede-Master',
            'Rede2-Visa',
            'Rede2-Master'
        ];

        // perform the changes

        $result = $this->model->getAllowedTypes();

        // test the results

        static::assertEquals($expected, $result);
    }
}

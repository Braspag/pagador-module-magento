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

use Webjump\BraspagPagador\Model\Source\VerticalSegment;

class VerticalSegmentTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        // mocks

        $this->model = $objectManager->getObject(
            VerticalSegment::class,
            []
        );
    }

    /** @test */
    public function test()
    {
        // prepare the test

        $expected = [
            [
                'value' => 'Retail',
                'label' => __('Retail'),
            ],
            [
                'value' => 'Cosmeticos',
                'label' => __('Cosmeticos')
            ],
            [
                'value' => 'Joalheria',
                'label' => __('Joalheria')
            ],
            [
                'value' => 'DigitalGoods',
                'label' => __('Digital Goods')
            ],
            [
                'value' => 'Servicos',
                'label' => __('Servicos')
            ],
            [
                'value' => 'Turismo',
                'label' => __('Turismo')
            ],
            [
                'value' => 'Generico',
                'label' => __('Generico')
            ]
        ];

        // perform the changes

        $result = $this->model->toOptionArray();

        // test the results

        static::assertEquals($expected, $result);
    }
}

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

use Webjump\BraspagPagador\Model\Source\SequenceCriteria;

class SequenceCriteriaTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        // mocks

        $this->model = $objectManager->getObject(
            SequenceCriteria::class,
            []
        );
    }

    /** @test */
    public function test()
    {
        // prepare the test

        $expected = [
            [
                'value' => 'OnSuccess',
                'label' => __('On Success'),
            ],
            [
                'value' => 'AuthorizeFirst',
                'label' => __('Always')
            ]
        ];

        // perform the changes

        $result = $this->model->toOptionArray();

        // test the results

        static::assertEquals($expected, $result);
    }
}

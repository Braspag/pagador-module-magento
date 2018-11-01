<?php

/**
 *
 * Silent Auth Token Test.php
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\SilentAuthToken;

class SilentAuthTokenTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->builderMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\BuilderInterface::class);

        $this->model = $objectManager->getObject(
            SilentAuthToken::class,
            [
                'builder' => $this->builderMock
            ]
        );
    }

    /** @test */
    public function test()
    {
        // prepare the test

        $expected = '123-456-789';

        $this->builderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue($expected));

        // perform the changes

        $result = $this->model->getToken();

        // test the results

        static::assertEquals($expected, $result);
    }
}

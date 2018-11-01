<?php

/**
 *
 * Installments Test.php
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\Installments;

class InstallmentsTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->builderMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface::class);
        $this->InstallmentMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface::class);

        $this->model = $objectManager->getObject(
            Installments::class,
            [
                'builder' => $this->builderMock
            ]
        );
    }

    /** @test */
    public function test()
    {
        // prepare the test

        $id = 1;
        $label = 'label';

        $this->InstallmentMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($id));

        $this->InstallmentMock->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue($label));

        $expected = [
            ['id' => $id, 'label' => $label]
        ];



        $this->builderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue([$this->InstallmentMock]));

        // perform the changes

        $result = $this->model->getInstallments();

        // test the results

        static::assertEquals($expected, $result);
    }
}

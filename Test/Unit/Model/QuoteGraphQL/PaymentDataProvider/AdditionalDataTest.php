<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\QuoteGraphQL\PaymentDataProvider;

use PHPUnit\Framework\TestCase;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Webjump\BraspagPagador\Model\QuoteGraphQL\PaymentDataProvider\AdditionalData;

class AdditionalDataTest extends TestCase
{
    const PATH_ADDITIONAL_DATA = 'additional_data';

    private $arrayManagerMock;

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->arrayManagerMock = $this->getMockBuilder(\Magento\Framework\Stdlib\ArrayManager::class)
            ->setMethods(['get','find'])
            ->getMock();


        $this->model = $objectManager->getObject(
            AdditionalData::class,
            [
                'arrayManager' => $this->arrayManagerMock
            ]
        );
    }

    public function testIfDataIndexIsEmptyShouldRetrieveException()
    {
        $data = [
            self::PATH_ADDITIONAL_DATA => [],
            'code' => 'braspag_pagador_creditcard'
        ];

        $this->arrayManagerMock
            ->expects($this->never())
            ->method('get')
            ->with(self::PATH_ADDITIONAL_DATA, $data);

        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "additional_data" for "payment_method" is missing.');

        $this->model->getData($data);
    }

    public function testIfDataIndexNotIsEmptyShouldReturnArrayWithData()
    {
        $expected = ['foo' => 'bar'];
        $data = [
            self::PATH_ADDITIONAL_DATA => ['foo'=> 'bar'],
            'code' => 'braspag_pagador_creditcard'
        ];

        $this->arrayManagerMock
            ->expects($this->once())
            ->method('get')
            ->with(self::PATH_ADDITIONAL_DATA, $data)
            ->willReturn(['foo' => 'bar']);

        $result = $this->model->getData($data);

        $this->assertEquals($expected, $result);
    }

}

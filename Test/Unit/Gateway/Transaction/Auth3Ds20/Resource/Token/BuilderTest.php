<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\Builder;
use Webjump\Braspag\Pagador\Transaction\Resource\Auth3Ds20\Token\Response as Auth3Ds20TokenResponse;


class BuilderTest extends \PHPUnit\Framework\TestCase
{
    private $builder;
    protected $dataObjectMock;
    protected $tokenResponseMock;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->dataObjectMock = $this->createMock(\Magento\Framework\DataObject::class);
        $this->tokenResponseMock = $this->createMock(Auth3Ds20TokenResponse::class);

        $this->builder = $this->objectManagerHelper->getObject(
            Builder::class,
            [
                'dataObject' => $this->dataObjectMock
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $this->tokenResponseMock->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue('123'));

        $this->tokenResponseMock->expects($this->once())
            ->method('getExpiresIn')
            ->will($this->returnValue('123'));

        static::assertEquals($this->dataObjectMock, $this->builder->build($this->tokenResponseMock));

    }
}

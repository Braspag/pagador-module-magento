<?php

namespace Braspag\BraspagPagador\Test\Unit\Gateway\Transaction\OAuth2\Command;

use Braspag\BraspagPagador\Gateway\Transaction\OAuth2\Command\TokenCommand;
use Braspag\BraspagPagador\Api\OAuth2TokenCommandInterface;
use Braspag\Braspag\Pagador\Transaction\BraspagFacade as BraspagApi;
use Braspag\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface;

class TokenCommandTest extends \PHPUnit\Framework\TestCase
{
    private $command;
    private $apiMock;
    private $responseHandlerMock;
    private $requestHandlerMock;

    public function setUp()
    {
        $this->apiMock = $this->createMock(BraspagApi::class);
        $this->requestBuilderMock = $this->createMock('Magento\Payment\Gateway\Request\BuilderInterface');
        $this->requestHandlerMock = $this->createMock('Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface');
        $this->responseHandlerMock = $this->createMock('Magento\Payment\Gateway\Response\HandlerInterface');

        $this->command = new TokenCommand(
            $this->apiMock
        );
    }

    public function tearDown()
    {
    }

    public function testExecute()
    {
        $buildObject = [];

        $requestMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\OAuth2\Token\ResponseInterface')
            ->getMock();

        $this->apiMock->expects($this->once())
            ->method('getOAuth2Token')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $result = $this->command->execute($requestMock);

        $this->assertEquals($responseMock, $result);
    }
}
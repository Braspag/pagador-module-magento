<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\Auth3Ds20TokenManager;

class Auth3Ds20TokenManagerTest extends \PHPUnit\Framework\TestCase
{
    private $model;
    private $requestMock;
    private $tokenCommandMock;
    private $cookieManagerMock;
    private $cookieMetadataFactoryMock;
    private $publicCookieMetadata;
    private $responseMock;
    private $builderMock;
    private $dataObjectMock;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->requestMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\RequestInterface::class);
        $this->tokenCommandMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command\TokenCommand::class);
        $this->cookieManagerMock = $this->createMock(\Magento\Framework\Stdlib\CookieManagerInterface::class);
        $this->publicCookieMetadata = $this->createMock(\Magento\Framework\Stdlib\Cookie\PublicCookieMetadata::class);

        $this->cookieMetadataFactoryMock = $this->getMockBuilder(\Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->createMock(\Webjump\Braspag\Pagador\Transaction\Resource\Auth3Ds20\Token\Response::class);
        $this->builderMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\BuilderInterface::class);
        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getExpiresIn', 'getToken'])
            ->getMock();

        $this->model = $objectManager->getObject(
            Auth3Ds20TokenManager::class,
            [
                'request' => $this->requestMock ,
                'tokenCommand' => $this->tokenCommandMock,
                'cookieManager' => $this->cookieManagerMock,
                'cookieMetadataFactory' => $this->cookieMetadataFactoryMock,
                'builder' => $this->builderMock,
                'dataObject' => $this->dataObjectMock
            ]
        );
    }

    /** @test */
    public function getTokenSuccessTest()
    {
        // prepare the test

        $tokenData = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1ZGZmYWMzMC1mZTE5LTQwMDctYWQ1Yy0yODhjYTRlNzRhNDciLCJpYXQiOjE1NTg1OTgyNTcsImlzcyI6IjViMWZjZGM4MGU0MjNkMTE3YzE3MWRlMCIsIk9yZ1VuaXRJZCI6IjViMWVkNGYwYWZhODBkMWZiMDY0NWU1YSIsIlBheWxvYWQiOiJ7XCJPcmRlckRldGFpbHNcIjp7XCJPcmRlck51bWJlclwiOlwiNTFcIixcIkFtb3VudFwiOlwiMTgwMFwiLFwiQ3VycmVuY3lDb2RlXCI6XCJCUkxcIn19IiwiT2JqZWN0aWZ5UGF5bG9hZCI6ZmFsc2UsImV4cCI6MTU1ODU5OTE1NywiUmVmZXJlbmNlSWQiOiI1NjM3ZmJkZS1hMmU2LTRjYmYtYmUxZC1iY2ZhNzllOGQ3YjYifQ.vxBAzA33ZD2Kfyn6il2YiTagaw4eExEUCClDinncKuQ';
        $expiresIn = 200;

        $this->cookieManagerMock
            ->expects($this->once())
            ->method('getCookie')
            ->with(\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\RequestInterface::BPMPI_ACCESS_TOKEN_COOKIE_NAME, false)
            ->will($this->returnValue(null));
        // perform the changes

        $this->tokenCommandMock->expects($this->once())
            ->method('execute')
            ->with($this->requestMock)
            ->will($this->returnValue($this->responseMock));

        $this->builderMock->expects($this->once())
            ->method('build')
            ->with($this->responseMock)
            ->will($this->returnValue($this->dataObjectMock));

        $this->dataObjectMock->expects($this->once())
            ->method('getExpiresIn')
            ->will($this->returnValue($expiresIn));

        $this->dataObjectMock->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($tokenData));

        $this->publicCookieMetadata->expects($this->once())
            ->method('setHttpOnly')
            ->with(true)
            ->will($this->returnSelf());

        $this->publicCookieMetadata->expects($this->once())
            ->method('setDuration')
            ->with($expiresIn)
            ->will($this->returnSelf());

        $this->publicCookieMetadata->expects($this->once())
            ->method('setPath')
            ->with('/')
            ->will($this->returnSelf());

        $this->cookieMetadataFactoryMock->expects($this->once())
            ->method('createPublicCookieMetadata')
            ->will($this->returnValue($this->publicCookieMetadata));

        $result = $this->model->getToken();

        // test the results

        static::assertEquals([['token' => $tokenData]], $result);
    }

    /** @test */
    public function getTokenFromCookieTest()
    {
        // prepare the test

        $tokenData = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1ZGZmYWMzMC1mZTE5LTQwMDctYWQ1Yy0yODhjYTRlNzRhNDciLCJpYXQiOjE1NTg1OTgyNTcsImlzcyI6IjViMWZjZGM4MGU0MjNkMTE3YzE3MWRlMCIsIk9yZ1VuaXRJZCI6IjViMWVkNGYwYWZhODBkMWZiMDY0NWU1YSIsIlBheWxvYWQiOiJ7XCJPcmRlckRldGFpbHNcIjp7XCJPcmRlck51bWJlclwiOlwiNTFcIixcIkFtb3VudFwiOlwiMTgwMFwiLFwiQ3VycmVuY3lDb2RlXCI6XCJCUkxcIn19IiwiT2JqZWN0aWZ5UGF5bG9hZCI6ZmFsc2UsImV4cCI6MTU1ODU5OTE1NywiUmVmZXJlbmNlSWQiOiI1NjM3ZmJkZS1hMmU2LTRjYmYtYmUxZC1iY2ZhNzllOGQ3YjYifQ.vxBAzA33ZD2Kfyn6il2YiTagaw4eExEUCClDinncKuQ';
        $expiresIn = 200;

        $this->cookieManagerMock
            ->expects($this->exactly(2))
            ->method('getCookie')
            ->with(\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\RequestInterface::BPMPI_ACCESS_TOKEN_COOKIE_NAME, false)
            ->will($this->returnValue($tokenData));

        // perform the changes

        $result = $this->model->getToken();

        // test the results

        static::assertEquals([['token' => $tokenData]], $result);
    }
}

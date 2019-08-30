<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    private $creaditCardConfig;

    protected $objectManagerHelper;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->configMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config\ConfigInterface');
        $this->installmentsconfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

        $this->request = $this->objectManagerHelper->getObject(
            Request::class
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $this->markTestIncomplete();

        $this->configMock->expects($this->once())
            ->method('getAccessToken')
            ->will($this->returnValue('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1ZGZmYWMzMC1mZTE5LTQwMDctYWQ1Yy0yODhjYTRlNzRhNDciLCJpYXQiOjE1NTg1OTgyNTcsImlzcyI6IjViMWZjZGM4MGU0MjNkMTE3YzE3MWRlMCIsIk9yZ1VuaXRJZCI6IjViMWVkNGYwYWZhODBkMWZiMDY0NWU1YSIsIlBheWxvYWQiOiJ7XCJPcmRlckRldGFpbHNcIjp7XCJPcmRlck51bWJlclwiOlwiNTFcIixcIkFtb3VudFwiOlwiMTgwMFwiLFwiQ3VycmVuY3lDb2RlXCI6XCJCUkxcIn19IiwiT2JqZWN0aWZ5UGF5bG9hZCI6ZmFsc2UsImV4cCI6MTU1ODU5OTE1NywiUmVmZXJlbmNlSWQiOiI1NjM3ZmJkZS1hMmU2LTRjYmYtYmUxZC1iY2ZhNzllOGQ3YjYifQ.vxBAzA33ZD2Kfyn6il2YiTagaw4eExEUCClDinncKuQ'));

        $this->configMock->expects($this->once())
            ->method('getMerchantName')
            ->will($this->returnValue('Loja Exemplo Ltda'));

        $this->configMock->expects($this->once())
            ->method('getEstablishmentCode')
            ->will($this->returnValue('1006993069'));

        $this->configMock->expects($this->once())
            ->method('getMCC')
            ->will($this->returnValue('5912'));

        $this->configMock->expects($this->once())
            ->method('getIsTestEnvironment')
            ->will($this->returnValue(''));

        static::assertEquals('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1ZGZmYWMzMC1mZTE5LTQwMDctYWQ1Yy0yODhjYTRlNzRhNDciLCJpYXQiOjE1NTg1OTgyNTcsImlzcyI6IjViMWZjZGM4MGU0MjNkMTE3YzE3MWRlMCIsIk9yZ1VuaXRJZCI6IjViMWVkNGYwYWZhODBkMWZiMDY0NWU1YSIsIlBheWxvYWQiOiJ7XCJPcmRlckRldGFpbHNcIjp7XCJPcmRlck51bWJlclwiOlwiNTFcIixcIkFtb3VudFwiOlwiMTgwMFwiLFwiQ3VycmVuY3lDb2RlXCI6XCJCUkxcIn19IiwiT2JqZWN0aWZ5UGF5bG9hZCI6ZmFsc2UsImV4cCI6MTU1ODU5OTE1NywiUmVmZXJlbmNlSWQiOiI1NjM3ZmJkZS1hMmU2LTRjYmYtYmUxZC1iY2ZhNzllOGQ3YjYifQ.vxBAzA33ZD2Kfyn6il2YiTagaw4eExEUCClDinncKuQ', $this->request->getAccessToken());
        static::assertEquals('Loja Exemplo Ltda', $this->request->getMerchantName());
        static::assertEquals('1006993069', $this->request->getEstablishmentCode());
        static::assertEquals('5912', $this->request->getMCC());
        static::assertTrue($this->request->getAccessToken());

    }
}

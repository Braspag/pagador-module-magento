<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\SilentOrderPost;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\Builder;

/**
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->configMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfigInterface');

        $this->transferBuilderMock = $this->createMock('Magento\Payment\Gateway\Http\TransferBuilder');

        $this->clientMock = $this->createMock('Magento\Payment\Gateway\Http\ClientInterface');

    	$this->builder =  new Builder(
            $this->configMock,
            $this->transferBuilderMock,
            $this->clientMock
        );
    }

    public function tearDown()
    {

    }

    public function testTest()
    {
    	$merchantId = 'BC5D3432-527F-40C6-84BF-C549285536BE';
    	$accessToken = 'ZTJlNDk1YzUtNzMwYy00ZjlkLTkzZTYtOWM5YWQxYTQ1YTc0LTIwOTE3NjI0NDY=';
        $uri = 'https://homologacao.pagador.com.br/post/api/public/v1/accesstoken?merchantid=BC5D3432-527F-40C6-84BF-C549285536BE';

        $this->tranferMock = $this->createMock('Magento\Payment\Gateway\Http\TransferInterface');

        $this->configMock->expects($this->once())
            ->method('getAccessTokenUrl')
            ->will($this->returnValue($uri));

        $this->transferBuilderMock->expects($this->once())
            ->method('setUri')
            ->with($uri)
            ->will($this->returnValue($this->transferBuilderMock));

        $this->transferBuilderMock->expects($this->once())
            ->method('setMethod')
            ->with('POST')
            ->will($this->returnValue($this->transferBuilderMock));

        $this->transferBuilderMock->expects($this->once())
            ->method('setClientConfig')
            ->with(['timeout' => 30])
            ->will($this->returnValue($this->transferBuilderMock));

        $this->transferBuilderMock->expects($this->once())
            ->method('setHeaders')
            ->with([
                  'cache-control' => 'no-cache',
                  'content-type' => 'application/json; charset=utf-8'
                ])
            ->will($this->returnValue($this->transferBuilderMock));

        $this->transferBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue($this->tranferMock));

        $this->responseMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\SilentOrderPostInterface');

        $this->responseMock->expects($this->once())
            ->method('getAccessToken')
            ->will($this->returnValue($accessToken));            

        $this->clientMock->expects($this->once())
            ->method('placeRequest')
            ->with($this->tranferMock)
            ->will($this->returnValue($this->responseMock));

    	$result = $this->builder->build();

    	static::assertEquals($accessToken, $result);
    }
}

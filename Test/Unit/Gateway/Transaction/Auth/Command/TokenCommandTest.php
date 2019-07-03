<?php
namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Auth3Ds20\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command\TokenCommand;

class TokenCommandTest extends \PHPUnit\Framework\TestCase
{
    private $command;
    private $apiMock;

    public function setUp()
    {
        $this->apiMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\FacadeInterface');

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

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface')
            ->getMock();

        $this->command->execute($requestMock);
    }
}
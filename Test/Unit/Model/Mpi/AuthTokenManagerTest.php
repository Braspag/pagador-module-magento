<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Test\Unit\Model\Mpi;

use Braspag\BraspagPagador\Gateway\Mpi\Command\AuthCommand;
use Braspag\BraspagPagador\Model\Mpi\AccessTokenCache;
use Braspag\BraspagPagador\Model\Mpi\AuthTokenManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Lock\LockManagerInterface;
use Magento\Framework\Phrase;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AuthTokenManagerTest extends TestCase
{
    /**
     * @var AccessTokenCache|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cache;

    /**
     * @var AuthCommand|\PHPUnit_Framework_MockObject_MockObject
     */
    private $authCommand;

    /**
     * @var LockManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $lockManager;

    /**
     * @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var AuthTokenManager
     */
    private $model;

    protected function setUp()
    {
        $this->cache = $this->getMockBuilder(AccessTokenCache::class)
            ->disableOriginalConstructor()->getMock();
        $this->authCommand = $this->getMockBuilder(AuthCommand::class)
            ->disableOriginalConstructor()->getMock();
        $this->lockManager = $this->getMockBuilder(LockManagerInterface::class)->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->model = new AuthTokenManager(
            $this->cache,
            $this->authCommand,
            $this->lockManager,
            $this->logger
        );
    }

    public function testReturnsCachedTokenWithoutHittingAuth()
    {
        $this->cache->method('get')->willReturn('cached-token');
        $this->authCommand->expects($this->never())->method('execute');
        $this->lockManager->expects($this->never())->method('lock');

        $this->assertSame('cached-token', $this->model->getAccessToken());
    }

    public function testIssuesFreshTokenWhenCacheEmpty()
    {
        $this->cache->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(null, null);

        $this->lockManager->method('lock')->willReturn(true);
        $this->lockManager->expects($this->once())
            ->method('unlock')
            ->with(AuthTokenManager::LOCK_NAME);

        $this->authCommand->method('execute')->willReturn([
            'access_token' => 'fresh-token',
            'expires_in' => 1200,
        ]);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('fresh-token', 1200);

        $this->assertSame('fresh-token', $this->model->getAccessToken());
    }

    public function testDoubleCheckAvoidsRedundantAuthUnderLock()
    {
        $this->cache->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(null, 'won-by-other');

        $this->lockManager->method('lock')->willReturn(true);
        $this->authCommand->expects($this->never())->method('execute');

        $this->assertSame('won-by-other', $this->model->getAccessToken());
    }

    public function testAuthCommandLocalizedExceptionsBubbleAndUnlock()
    {
        $this->cache->method('get')->willReturn(null);
        $this->lockManager->method('lock')->willReturn(true);
        $this->lockManager->expects($this->once())->method('unlock');

        $this->authCommand->method('execute')->willThrowException(
            new LocalizedException(new Phrase('AUTH boom'))
        );

        $this->expectException(LocalizedException::class);
        $this->model->getAccessToken();
    }

    public function testInvalidateDelegatesToCache()
    {
        $this->cache->expects($this->once())->method('remove');
        $this->model->invalidate();
    }
}

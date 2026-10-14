<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Test\Unit\Model\Mpi;

use Braspag\BraspagPagador\Model\Mpi\AccessTokenCache;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\TestCase;

class AccessTokenCacheTest extends TestCase
{
    /**
     * @var CacheInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cache;

    /**
     * @var EncryptorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $encryptor;

    /**
     * @var Json|\PHPUnit_Framework_MockObject_MockObject
     */
    private $json;

    /**
     * @var AccessTokenCache
     */
    private $model;

    protected function setUp()
    {
        $this->cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $this->encryptor = $this->getMockBuilder(EncryptorInterface::class)->getMock();
        $this->json = $this->getMockBuilder(Json::class)->disableOriginalConstructor()->getMock();

        $this->model = new AccessTokenCache($this->cache, $this->encryptor, $this->json);
    }

    public function testGetReturnsNullWhenCacheEmpty()
    {
        $this->cache->method('load')->willReturn(false);
        $this->assertNull($this->model->get());
    }

    public function testGetReturnsNullAndPurgesWhenExpired()
    {
        $payload = 'serialized-payload';
        $this->cache->method('load')->willReturn($payload);
        $this->json->method('unserialize')->with($payload)->willReturn([
            't' => 'enc-token',
            'e' => time() - 60,
        ]);
        $this->cache->expects($this->once())->method('remove')->with(AccessTokenCache::CACHE_ID);

        $this->assertNull($this->model->get());
    }

    public function testGetReturnsDecryptedTokenWhenValid()
    {
        $payload = 'serialized-payload';
        $this->cache->method('load')->willReturn($payload);
        $this->json->method('unserialize')->with($payload)->willReturn([
            't' => 'enc-token',
            'e' => time() + 600,
        ]);
        $this->encryptor->method('decrypt')->with('enc-token')->willReturn('plain-token');

        $this->assertSame('plain-token', $this->model->get());
    }

    public function testSetClampsTtlToMaxCheckoutWindow()
    {
        // Cielo devolve expires_in de 20 min (1200s); depois da margem sobrariam
        // 900s, mas o MAX_TTL_SECONDS clampa em 300s para evitar reutilização
        // do token entre checkouts distintos.
        $expiresIn = 1200;
        $expectedTtl = AccessTokenCache::MAX_TTL_SECONDS;

        $this->encryptor->method('encrypt')->with('plain-token')->willReturn('enc-token');
        $this->json->method('serialize')->willReturn('serialized-payload');

        $this->cache->expects($this->once())
            ->method('save')
            ->with(
                'serialized-payload',
                AccessTokenCache::CACHE_ID,
                [AccessTokenCache::CACHE_TAG],
                $expectedTtl
            );

        $this->model->set('plain-token', $expiresIn);
    }

    public function testSetClampsTtlToOneWhenExpiryTooShort()
    {
        $this->encryptor->method('encrypt')->willReturn('enc');
        $this->json->method('serialize')->willReturn('ser');

        $this->cache->expects($this->once())
            ->method('save')
            ->with('ser', AccessTokenCache::CACHE_ID, [AccessTokenCache::CACHE_TAG], 1);

        $this->model->set('t', 10);
    }

    public function testRemoveDropsCacheEntry()
    {
        $this->cache->expects($this->once())->method('remove')->with(AccessTokenCache::CACHE_ID);
        $this->model->remove();
    }
}

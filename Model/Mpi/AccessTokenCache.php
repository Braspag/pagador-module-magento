<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Cache "janela de checkout" para o access_token MPI V3.
 *
 * Design:
 * - Usa o cache DEFAULT do Magento (`Magento\Framework\App\CacheInterface`),
 *   que sempre está habilitado — o lojista não precisa ligar nada.
 * - TTL curto (`MAX_TTL_SECONDS`) porque cada `access_token` da Cielo é
 *   amarrado a UMA session Cielo (o JWT carrega o ReferenceId como claim).
 *   Se reutilizado entre checkouts, a Cielo devolve HTTP 409 no 2º INIT.
 *   Por isso mantemos o token vivo só o tempo suficiente pra INIT+ENROLL
 *   do mesmo checkout se completarem. O `EnrollManager` também invalida
 *   explicitamente ao final, para não deixar "sujeira".
 * - Valor gravado é criptografado em repouso (defense-in-depth).
 */
class AccessTokenCache
{
    /**
     * Cache identifier.
     */
    const CACHE_ID = 'braspag_mpi_access_token_v3';

    /**
     * Cache tag — permite `bin/magento cache:clean BRASPAG_MPI_V3`.
     */
    const CACHE_TAG = 'BRASPAG_MPI_V3';

    /**
     * Janela máxima em que o token fica válido no cache — 5 minutos. Cobre
     * um checkout normal (INIT → coleta de cartão → clique em Finalizar →
     * ENROLL) sem manter o token vivo entre múltiplos checkouts.
     */
    const MAX_TTL_SECONDS = 300;

    /**
     * Margem defensiva descontada de `expires_in` da Cielo — nunca servir
     * token nos últimos 5 min de vida real.
     */
    const EXPIRY_MARGIN_SECONDS = 300;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param CacheInterface     $cache Cache default do Magento (sempre ativo)
     * @param EncryptorInterface $encryptor
     * @param Json               $json
     */
    public function __construct(
        CacheInterface $cache,
        EncryptorInterface $encryptor,
        Json $json
    ) {
        $this->cache = $cache;
        $this->encryptor = $encryptor;
        $this->json = $json;
    }

    /**
     * Returns the cached token if still within its usable window, or null.
     *
     * @return string|null
     */
    public function get()
    {
        $payload = $this->cache->load(self::CACHE_ID);
        if (!$payload) {
            return null;
        }

        try {
            $data = $this->json->unserialize($payload);
        } catch (\Exception $e) {
            $this->remove();
            return null;
        }

        if (!is_array($data) || empty($data['t']) || empty($data['e'])) {
            $this->remove();
            return null;
        }

        // e = expiresAt (unix, already discounted from Cielo's expires_in by the margin).
        if ((int) $data['e'] <= time()) {
            $this->remove();
            return null;
        }

        $token = $this->encryptor->decrypt((string) $data['t']);
        return $token !== '' ? $token : null;
    }

    /**
     * Stores a freshly-issued token. TTL efetivo é o menor entre:
     *   - MAX_TTL_SECONDS (janela de checkout, 5 min);
     *   - $expiresIn - EXPIRY_MARGIN_SECONDS (o que a Cielo permite).
     *
     * A janela curta é intencional: cada access_token da Cielo é amarrado a
     * uma session; reutilizá-lo em outro checkout resulta em HTTP 409 no
     * INIT seguinte. O EnrollManager também chama invalidate() ao fim de
     * cada ENROLL para descartar o token proativamente.
     *
     * @param string $token
     * @param int    $expiresIn Seconds returned by Cielo (may be 0 if absent)
     */
    public function set($token, $expiresIn)
    {
        $cieloTtl = max(1, (int) $expiresIn - self::EXPIRY_MARGIN_SECONDS);
        $usableTtl = min(self::MAX_TTL_SECONDS, $cieloTtl);
        $expiresAt = time() + $usableTtl;

        $payload = $this->json->serialize([
            't' => $this->encryptor->encrypt($token),
            'e' => $expiresAt,
        ]);

        $this->cache->save(
            $payload,
            self::CACHE_ID,
            [self::CACHE_TAG],
            $usableTtl
        );
    }

    /**
     * Drops the cached token — used both on 401 responses from downstream MPI
     * calls and when the manager decides the cached copy is unusable.
     */
    public function remove()
    {
        $this->cache->remove(self::CACHE_ID);
    }
}

<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Command;

use Braspag\BraspagPagador\Api\MpiV3AuthTokenManagerInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Init\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * POST /v3/3ds/init — returns { referenceId, token(JWT) } to boot MPI on the
 * browser. Handles 401 by invalidating the access_token once and retrying.
 */
class InitCommand extends AbstractCommand
{
    /**
     * @var MpiV3AuthTokenManagerInterface
     */
    private $authTokenManager;

    /**
     * @param CurlFactory                    $curlFactory
     * @param Json                           $json
     * @param LoggerInterface                $logger
     * @param ConfigInterface                $config
     * @param MpiV3AuthTokenManagerInterface $authTokenManager
     */
    public function __construct(
        CurlFactory $curlFactory,
        Json $json,
        LoggerInterface $logger,
        ConfigInterface $config,
        MpiV3AuthTokenManagerInterface $authTokenManager
    ) {
        parent::__construct($curlFactory, $json, $logger, $config);
        $this->authTokenManager = $authTokenManager;
    }

    /**
     * @param RequestInterface $request
     * @return array{referenceId:string, token:string}
     * @throws LocalizedException
     */
    public function execute(RequestInterface $request)
    {
        $result = $this->send($request, $this->authTokenManager->getAccessToken());

        if ($result['status'] === 401) {
            // Token expired between check and use — refresh once and retry.
            $this->authTokenManager->invalidate();
            $result = $this->send($request, $this->authTokenManager->getAccessToken());
        }

        if ($result['status'] === 409) {
            // Session Cielo já consumida — tipicamente acontece quando o
            // ENROLL anterior retornou Status=2 (challenge) e o VALIDATE
            // nunca veio (user abandonou a popup). O access_token em cache
            // ainda aponta pra aquela session gasta e todo INIT novo bate
            // 409. Invalida o token, pega um novo (AUTH fresh = session Cielo
            // nova) e retenta UMA vez.
            $this->authTokenManager->invalidate();
            $result = $this->send($request, $this->authTokenManager->getAccessToken());
        }

        if ($result['status'] !== 200) {
            throw new LocalizedException(new Phrase(
                'MPI V3 INIT failed with HTTP %1.',
                [$result['status']]
            ));
        }

        $body = $result['body'];
        // Cielo sandbox observed in 2026-09-28 returns PascalCase keys
        // (`ReferenceId`, `Token`), while the doc example shows camelCase
        // (`referenceId`, `token`). Accept both to avoid surprises across
        // sandbox / prod / doc drift.
        $referenceId = '';
        $token = '';
        if (is_array($body)) {
            $referenceId = (string) ($body['referenceId'] ?? $body['ReferenceId'] ?? '');
            $token       = (string) ($body['token'] ?? $body['Token'] ?? '');
        }

        if ($referenceId === '' || $token === '') {
            throw new LocalizedException(new Phrase(
                'MPI V3 INIT returned an unexpected payload (referenceId/token missing).'
            ));
        }

        return [
            'referenceId' => $referenceId,
            'token'       => $token,
        ];
    }

    /**
     * @param RequestInterface $request
     * @param string           $bearerToken
     * @return array
     */
    private function send(RequestInterface $request, $bearerToken)
    {
        return $this->request(
            'POST',
            $this->config->getApiBase() . '/3ds/init',
            [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            $request->toArray()
        );
    }
}

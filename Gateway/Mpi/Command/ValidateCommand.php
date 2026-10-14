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
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * POST /v3/3ds/validate — post-challenge step that returns the definitive
 * Cavv/Xid/Eci/Version/DirectoryServerTransactionId. Response envelope is
 * normalized to the same shape ENROLL returns for the `authentication` key.
 */
class ValidateCommand extends AbstractCommand
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
     * @return array{status:int, reasonCode:string, reasonMessage:string, authentication:?array}
     * @throws LocalizedException
     */
    public function execute(RequestInterface $request)
    {
        $result = $this->send($request, $this->authTokenManager->getAccessToken());
        if ($result['status'] === 401) {
            $this->authTokenManager->invalidate();
            $result = $this->send($request, $this->authTokenManager->getAccessToken());
        }

        if ($result['status'] !== 200) {
            throw new LocalizedException(new Phrase(
                'MPI V3 VALIDATE failed with HTTP %1.',
                [$result['status']]
            ));
        }

        return $this->normalize($result['body']);
    }

    /**
     * @param mixed $body
     * @return array
     */
    private function normalize($body)
    {
        if (!is_array($body)) {
            return [
                'status'         => 0,
                'reasonCode'     => 'PARSE',
                'reasonMessage'  => 'Malformed VALIDATE response',
                'authentication' => null,
            ];
        }

        // Cielo sandbox retorna `Status` PascalCase (mesmo padrão do INIT e
        // do ENROLL). Aceitar ambos.
        $status = isset($body['Status']) ? (int) $body['Status']
                : (isset($body['status']) ? (int) $body['status'] : 0);
        $reason = isset($body['Reason']) && is_array($body['Reason']) ? $body['Reason'] : [];
        $auth = isset($body['Authentication']) && is_array($body['Authentication']) ? $body['Authentication'] : null;

        return [
            'status'         => $status,
            'reasonCode'     => isset($reason['Code']) ? (string) $reason['Code'] : '',
            'reasonMessage'  => isset($reason['Message']) ? (string) $reason['Message'] : '',
            'authentication' => $auth ? [
                'cavv'                          => (string) ($auth['Cavv'] ?? ''),
                'xid'                           => (string) ($auth['Xid'] ?? ''),
                'eci'                           => (string) ($auth['Eci'] ?? ''),
                'version'                       => (string) ($auth['Version'] ?? ''),
                'directoryServerTransactionId'  => (string) ($auth['DirectoryServerTransactionId'] ?? ''),
            ] : null,
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
            $this->config->getApiBase() . '/3ds/validate',
            [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            $request->toArray()
        );
    }
}

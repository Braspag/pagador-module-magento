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
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Enroll\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * POST /v3/3ds/enroll. Returns a normalized envelope so the manager doesn't
 * need to know about Cielo's PascalCase quirks. Handles 401 by refreshing
 * AUTH once and retrying.
 */
class EnrollCommand extends AbstractCommand
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
     * @return array{status:int, reasonCode:string, reasonMessage:string, challenge:?array, authentication:?array}
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
            // HTTP 409 = OrderNumber já foi enrollado antes pela Cielo. Comum
            // em ambientes de teste quando o mesmo carrinho é reutilizado
            // após um checkout falho: a quote mantém o mesmo reservedOrderId,
            // e a Cielo não deixa enrollar duas vezes com esse número.
            if ($result['status'] === 409) {
                throw new LocalizedException(new Phrase(
                    'MPI V3 ENROLL retornou HTTP 409 (Conflict). Provavelmente o número do pedido já foi autenticado em uma tentativa anterior. Refaça o carrinho (adicione o produto de novo, ou limpe e recomece) para gerar um novo número de pedido.'
                ));
            }
            throw new LocalizedException(new Phrase(
                'MPI V3 ENROLL failed with HTTP %1.',
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
            return ['status' => 0, 'reasonCode' => 'PARSE', 'reasonMessage' => 'Malformed ENROLL response', 'challenge' => null, 'authentication' => null];
        }

        // Cielo sandbox retorna `Status` em PascalCase (mesmo padrão do INIT
        // com `ReferenceId`/`Token`). A doc mostra `status` em camelCase mas
        // o runtime real usa PascalCase. Aceitar ambos.
        $status = isset($body['Status']) ? (int) $body['Status']
                : (isset($body['status']) ? (int) $body['status'] : 0);
        $reason = isset($body['Reason']) && is_array($body['Reason']) ? $body['Reason'] : [];
        $challenge = isset($body['Challenge']) && is_array($body['Challenge']) ? $body['Challenge'] : null;
        $authentication = isset($body['Authentication']) && is_array($body['Authentication']) ? $body['Authentication'] : null;

        return [
            'status'         => $status,
            'reasonCode'     => isset($reason['Code']) ? (string) $reason['Code'] : '',
            'reasonMessage'  => isset($reason['Message']) ? (string) $reason['Message'] : '',
            'challenge'      => $challenge ? [
                'acsUrl'        => (string) ($challenge['AcsUrl'] ?? ''),
                'pareq'         => (string) ($challenge['Pareq'] ?? ''),
                'transactionId' => (string) ($challenge['TransactionId'] ?? ''),
            ] : null,
            'authentication' => $authentication ? $this->normalizeAuthentication($authentication) : null,
        ];
    }

    /**
     * @param array $auth
     * @return array
     */
    private function normalizeAuthentication(array $auth)
    {
        return [
            'cavv'                          => (string) ($auth['Cavv'] ?? ''),
            'xid'                           => (string) ($auth['Xid'] ?? ''),
            'eci'                           => (string) ($auth['Eci'] ?? ''),
            'version'                       => (string) ($auth['Version'] ?? ''),
            'directoryServerTransactionId'  => (string) ($auth['DirectoryServerTransactionId'] ?? ''),
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
            $this->config->getApiBase() . '/3ds/enroll',
            [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            $request->toArray()
        );
    }
}

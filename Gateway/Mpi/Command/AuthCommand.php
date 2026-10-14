<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Command;

use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth\BuilderInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Issues the MPI V3 AUTH request (POST /v3/auth/token).
 *
 * Response contract per Cielo documentation:
 *   200 OK -> { "access_token": "...", "token_type": "bearer", "expires_in": "1200" }
 * Note that `expires_in` is returned as a string containing seconds.
 */
class AuthCommand extends AbstractCommand
{
    /**
     * @var BuilderInterface
     */
    private $requestBuilder;

    /**
     * @param CurlFactory      $curlFactory
     * @param Json             $json
     * @param LoggerInterface  $logger
     * @param ConfigInterface  $config
     * @param BuilderInterface $requestBuilder
     */
    public function __construct(
        CurlFactory $curlFactory,
        Json $json,
        LoggerInterface $logger,
        ConfigInterface $config,
        BuilderInterface $requestBuilder
    ) {
        parent::__construct($curlFactory, $json, $logger, $config);
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * Executes AUTH and returns the raw response body plus the resolved TTL.
     *
     * @return array{access_token:string, expires_in:int}
     * @throws LocalizedException When AUTH fails or the response is malformed.
     */
    public function execute()
    {
        $authRequest = $this->requestBuilder->build();

        $result = $this->request(
            'POST',
            $this->config->getAuthEndpoint(),
            [
                'Authorization' => 'Basic ' . $authRequest->getBasicAuthCredential(),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            $authRequest->toArray()
        );

        if ($result['status'] !== 200) {
            // Body is not surfaced in the message on purpose — the admin sees
            // the exception message and the response body can carry sensitive
            // data. Full response is in var/log/braspag_mpi_v3.log when Debug
            // Log is toggled on in admin.
            throw new LocalizedException(new Phrase(
                'MPI V3 AUTH failed with HTTP %1.',
                [$result['status']]
            ));
        }

        $body = $result['body'];
        if (!is_array($body) || empty($body['access_token'])) {
            throw new LocalizedException(new Phrase(
                'MPI V3 AUTH returned an unexpected payload (no access_token).'
            ));
        }

        $expiresIn = isset($body['expires_in']) ? (int) $body['expires_in'] : 0;

        return [
            'access_token' => (string) $body['access_token'],
            'expires_in'   => $expiresIn,
        ];
    }
}

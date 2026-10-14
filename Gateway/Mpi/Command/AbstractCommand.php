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
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Base for MPI V3 HTTP commands.
 *
 * Owns the shared plumbing: JSON serialization, sensitive-header redaction for
 * logging, request/response logging around every call, and the low-level HTTP
 * client instantiation. Subclasses only decide the URL, method and body.
 *
 * Uses Magento's own Curl client so the module carries no extra dependencies
 * and can be unit-tested by mocking CurlFactory.
 */
abstract class AbstractCommand
{
    /**
     * Header names to redact when logging. Comparison is case-insensitive.
     */
    const REDACTED_HEADERS = ['authorization', 'x-access-token'];

    /**
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param CurlFactory     $curlFactory
     * @param Json            $json
     * @param LoggerInterface $logger
     * @param ConfigInterface $config
     */
    public function __construct(
        CurlFactory $curlFactory,
        Json $json,
        LoggerInterface $logger,
        ConfigInterface $config
    ) {
        $this->curlFactory = $curlFactory;
        $this->json = $json;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Issues an HTTP request and returns the decoded JSON body.
     *
     * @param string $method HTTP method (POST/GET/PUT/...)
     * @param string $url    Absolute URL
     * @param array  $headers Header map: name => value
     * @param array|null $body Body to JSON-encode; null means no body
     * @return array{status:int, body:array|null, raw:string, latency_ms:float}
     */
    protected function request($method, $url, array $headers, $body = null)
    {
        $curl = $this->curlFactory->create();
        $curl->setOption(CURLOPT_TIMEOUT, 30);
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 10);

        $sentBody = null;
        if ($body !== null) {
            $sentBody = $this->json->serialize($body);
        }

        $curl->setHeaders($headers);

        $traceId = $this->traceId();
        $this->logRequest($traceId, $method, $url, $headers, $body);

        $start = microtime(true);
        switch (strtoupper($method)) {
            case 'POST':
                $curl->post($url, $sentBody ?? '');
                break;
            case 'GET':
                $curl->get($url);
                break;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'MPI V3 command does not support HTTP method "%s".',
                    $method
                ));
        }
        $latencyMs = (microtime(true) - $start) * 1000;

        $status = (int) $curl->getStatus();
        $rawBody = (string) $curl->getBody();
        $decoded = null;
        if ($rawBody !== '') {
            try {
                $decoded = $this->json->unserialize($rawBody);
            } catch (\Exception $e) {
                $decoded = null;
            }
        }

        $this->logResponse($traceId, $method, $url, $status, $rawBody, $decoded, $latencyMs);

        return [
            'status'     => $status,
            'body'       => $decoded,
            'raw'        => $rawBody,
            'latency_ms' => $latencyMs,
        ];
    }

    /**
     * Short random id shared between the request and response log lines so
     * grep on `[trace=abc123]` shows the full round trip.
     *
     * @return string
     */
    private function traceId()
    {
        try {
            return substr(bin2hex(random_bytes(4)), 0, 8);
        } catch (\Exception $e) {
            return substr(md5((string) microtime(true)), 0, 8);
        }
    }

    /**
     * @param string     $traceId
     * @param string     $method
     * @param string     $url
     * @param array      $headers
     * @param array|null $body Structured body — logged AFTER PAN redaction
     */
    private function logRequest($traceId, $method, $url, array $headers, $body)
    {
        if (!$this->config->isDebugActive()) {
            return;
        }

        $safeBody = $body === null ? '<empty>' : $this->json->serialize($this->redactSensitiveBody($body));

        $this->logger->debug(sprintf(
            '[trace=%s] REQUEST %s %s | headers=%s | body=%s',
            $traceId,
            $method,
            $url,
            $this->json->serialize($this->redactHeaders($headers)),
            $safeBody
        ));
    }

    /**
     * Masks card number keys (PAN) before the body reaches the log.
     * Keeps first 6 + last 4 to help debugging (BIN + last4 is allowed by PCI).
     *
     * @param array $body
     * @return array
     */
    private function redactSensitiveBody(array $body)
    {
        foreach ($body as $key => $value) {
            if (is_array($value)) {
                $body[$key] = $this->redactSensitiveBody($value);
                continue;
            }
            $lower = strtolower((string) $key);
            if ($lower === 'cardnumber' && is_string($value) && strlen($value) >= 10) {
                $body[$key] = substr($value, 0, 6) . str_repeat('*', max(0, strlen($value) - 10)) . substr($value, -4);
            }
        }
        return $body;
    }

    /**
     * @param string $traceId
     * @param string $method
     * @param string $url
     * @param int    $status
     * @param string $rawBody
     * @param mixed  $decoded
     * @param float  $latencyMs
     */
    private function logResponse($traceId, $method, $url, $status, $rawBody, $decoded, $latencyMs)
    {
        if (!$this->config->isDebugActive()) {
            // Non-200s are always noteworthy — surface as warning even without
            // debug on, so the store owner has SOMETHING when Cielo starts
            // rejecting silently.
            if ($status !== 200) {
                $this->logger->warning(sprintf(
                    '[Braspag MPI V3] %s %s -> HTTP %d in %dms',
                    $method,
                    $url,
                    $status,
                    (int) $latencyMs
                ));
            }
            return;
        }

        $this->logger->debug(sprintf(
            '[trace=%s] RESPONSE %s %s -> HTTP %d in %dms | body=%s%s',
            $traceId,
            $method,
            $url,
            $status,
            (int) $latencyMs,
            $rawBody !== '' ? $rawBody : '<empty>',
            $this->summarizeResult($decoded)
        ));
    }

    /**
     * Pulls the fields the operator normally cares about (status / Reason /
     * Challenge / Authentication) and prints them on a compact single-line
     * summary. Makes the log grep-friendly when the raw body is huge.
     *
     * @param mixed $decoded
     * @return string Prefixed with a leading " | " when there is anything to
     *         show, or an empty string otherwise
     */
    private function summarizeResult($decoded)
    {
        if (!is_array($decoded)) {
            return '';
        }

        $parts = [];
        if (isset($decoded['status'])) {
            $parts[] = 'status=' . $decoded['status'];
        }
        if (isset($decoded['Reason']) && is_array($decoded['Reason'])) {
            $parts[] = 'reason=' . ($decoded['Reason']['Code'] ?? '?')
                . '/' . ($decoded['Reason']['Message'] ?? '?');
        }
        if (isset($decoded['Challenge']) && is_array($decoded['Challenge'])) {
            $parts[] = 'challenge.transactionId=' . ($decoded['Challenge']['TransactionId'] ?? '?');
        }
        if (isset($decoded['Authentication']) && is_array($decoded['Authentication'])) {
            $parts[] = 'auth.eci=' . ($decoded['Authentication']['Eci'] ?? '?')
                . ' auth.version=' . ($decoded['Authentication']['Version'] ?? '?');
        }
        if (isset($decoded['access_token'])) {
            $parts[] = 'access_token=<' . strlen((string) $decoded['access_token']) . ' chars>';
        }
        if (isset($decoded['referenceId'])) {
            $parts[] = 'referenceId=' . $decoded['referenceId'];
        }

        return $parts ? ' | ' . implode(' ', $parts) : '';
    }

    /**
     * @param array $headers
     * @return array
     */
    private function redactHeaders(array $headers)
    {
        $redacted = [];
        foreach ($headers as $name => $value) {
            $redacted[$name] = in_array(strtolower((string) $name), self::REDACTED_HEADERS, true)
                ? '***REDACTED***'
                : $value;
        }
        return $redacted;
    }
}

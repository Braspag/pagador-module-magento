<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Api\Data;

/**
 * Envelope da resposta ENROLL. `status` indica o caminho: 0=falha,
 * 1=frictionless success, 2=challenge. `challenge` só é preenchido quando
 * status=2. `authentication` só é preenchido em status=1 (frictionless).
 */
interface MpiV3EnrollResultInterface
{
    const STATUS         = 'status';
    const REASON_CODE    = 'reason_code';
    const REASON_MESSAGE = 'reason_message';
    const CHALLENGE      = 'challenge';
    const AUTHENTICATION = 'authentication';

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getReasonCode();

    /**
     * @param string $reasonCode
     * @return $this
     */
    public function setReasonCode($reasonCode);

    /**
     * @return string
     */
    public function getReasonMessage();

    /**
     * @param string $reasonMessage
     * @return $this
     */
    public function setReasonMessage($reasonMessage);

    /**
     * @return \Braspag\BraspagPagador\Api\Data\MpiV3ChallengeInterface|null
     */
    public function getChallenge();

    /**
     * @param \Braspag\BraspagPagador\Api\Data\MpiV3ChallengeInterface|null $challenge
     * @return $this
     */
    public function setChallenge($challenge);

    /**
     * @return \Braspag\BraspagPagador\Api\Data\MpiV3AuthenticationInterface|null
     */
    public function getAuthentication();

    /**
     * @param \Braspag\BraspagPagador\Api\Data\MpiV3AuthenticationInterface|null $authentication
     * @return $this
     */
    public function setAuthentication($authentication);
}

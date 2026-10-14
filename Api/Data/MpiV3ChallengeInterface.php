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
 * Retornado pelo ENROLL quando status=2 (challenge). O browser usa esses
 * campos para abrir o iframe do desafio 3DS via MPI.challenge().
 */
interface MpiV3ChallengeInterface
{
    const ACS_URL         = 'acs_url';
    const PAREQ           = 'pareq';
    const TRANSACTION_ID  = 'transaction_id';

    /**
     * @return string
     */
    public function getAcsUrl();

    /**
     * @param string $acsUrl
     * @return $this
     */
    public function setAcsUrl($acsUrl);

    /**
     * @return string
     */
    public function getPareq();

    /**
     * @param string $pareq
     * @return $this
     */
    public function setPareq($pareq);

    /**
     * @return string
     */
    public function getTransactionId();

    /**
     * @param string $transactionId
     * @return $this
     */
    public function setTransactionId($transactionId);
}

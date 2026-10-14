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
 * Autenticação retornada pela Cielo em ENROLL frictionless success e em
 * VALIDATE (pós-challenge). Contém os campos que serão gravados como
 * `Payment.ExternalAuthentication` na chamada /v2/sales/.
 */
interface MpiV3AuthenticationInterface
{
    const CAVV                              = 'cavv';
    const XID                               = 'xid';
    const ECI                               = 'eci';
    const VERSION                           = 'version';
    const DIRECTORY_SERVER_TRANSACTION_ID   = 'directory_server_transaction_id';

    /**
     * @return string
     */
    public function getCavv();

    /**
     * @param string $cavv
     * @return $this
     */
    public function setCavv($cavv);

    /**
     * @return string
     */
    public function getXid();

    /**
     * @param string $xid
     * @return $this
     */
    public function setXid($xid);

    /**
     * @return string
     */
    public function getEci();

    /**
     * @param string $eci
     * @return $this
     */
    public function setEci($eci);

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * @return string
     */
    public function getDirectoryServerTransactionId();

    /**
     * @param string $directoryServerTransactionId
     * @return $this
     */
    public function setDirectoryServerTransactionId($directoryServerTransactionId);
}

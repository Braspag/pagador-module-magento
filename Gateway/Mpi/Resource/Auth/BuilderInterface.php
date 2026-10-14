<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth;

/**
 * Builds the AUTH request from admin configuration.
 */
interface BuilderInterface
{
    /**
     * @return \Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth\RequestInterface
     * @throws \Magento\Framework\Exception\LocalizedException When any of the
     *         required admin fields (client id/secret, establishment code,
     *         merchant name, MCC) is missing.
     */
    public function build();
}

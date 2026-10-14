<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Enroll;

/**
 * ENROLL body carrier. The payload is large; we keep it as a single array
 * and expose it verbatim via toArray() so command layer stays generic.
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function toArray();
}

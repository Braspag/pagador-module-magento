<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\SplitInterface;

interface SplitManagerInterface
{
    const DEFAULT_TIME_FROM_TO_SEND_TRANSACTIONAL_POST = '00:00:00';
    const DEFAULT_TIME_TO_TO_SEND_TRANSACTIONAL_POST = '01:30:00';
}

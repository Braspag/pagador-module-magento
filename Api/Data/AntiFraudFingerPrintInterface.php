<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Api\Data;


interface AntiFraudFingerPrintInterface
{
    public function getSrcPngImageUrl();

    public function getSrcFlashUrl();

    public function getSrcJsUrl();

    public function getOrgId();
    
    public function getSessionId();
}

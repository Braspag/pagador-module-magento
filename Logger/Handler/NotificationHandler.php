<?php

namespace Braspag\BraspagPagador\Logger\Handler;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger;

class NotificationHandler extends BaseHandler
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * Log file name
     * @var string
     */
    protected $fileName = '/var/log/Braspag-braspag-notification.log';
}

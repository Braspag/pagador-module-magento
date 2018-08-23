<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTime as CurrentDate;

class ContextAdmin extends Context
{}
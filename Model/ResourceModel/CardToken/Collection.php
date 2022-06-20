<?php

namespace Braspag\BraspagPagador\Model\ResourceModel\CardToken;

use Braspag\BraspagPagador\Model\ResourceModel\CardToken as CardTokenResourceModel;
use Braspag\BraspagPagador\Model\CardToken as CardTokenModel;

/**
 * Card Token Collection
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            CardTokenModel::class,
            CardTokenResourceModel::class
        );
    }
}
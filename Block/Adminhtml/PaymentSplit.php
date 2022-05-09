<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    \Unirgy\Dropship
 * @copyright  Copyright (c) 2015-2016 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

namespace Webjump\BraspagPagador\Block\Adminhtml;

use \Magento\Backend\Block\Widget\Grid\Container;

class PaymentSplit extends Container
{
    public function _construct()
    {
        $this->_blockGroup = 'Webjump_BraspagPagador';
        $this->_controller = 'adminhtml_paymentsplit';
        $this->_headerText = __('Manage Payment Splits');
        parent::_construct();

        $this->removeButton('add');
    }
}

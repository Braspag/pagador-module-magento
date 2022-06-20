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
 * @package    \Braspag\BraspagPagador
 * @copyright  Copyright (c) 2015-2016 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

namespace Braspag\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

/**
 * Class Tabs
 * @package Braspag\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit
 * @codeCoverageIgnore
 */
class Tabs extends WidgetTabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Payment Split'));
    }
}